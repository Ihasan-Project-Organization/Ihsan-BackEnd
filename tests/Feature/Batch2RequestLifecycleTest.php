<?php

use App\Models\Complaint;
use App\Models\ElderProfile;
use App\Models\Notification;
use App\Models\Rating;
use App\Models\ServiceProviderProfile;
use App\Models\ServiceRequest;
use App\Models\User;

beforeEach(function () {
    //
});

function createElderUser(): array {
    $user = User::factory()->create(['status' => 'approved']);
    $profile = ElderProfile::create([
        'user_id' => $user->id,
        'full_name' => $user->name,
        'city' => 'غزة',
        'phone_number' => '0599111222',
    ]);
    return [$user, $profile];
}

function createProviderUser(): array {
    $user = User::factory()->create(['status' => 'approved']);
    $profile = ServiceProviderProfile::create([
        'user_id' => $user->id,
        'full_name' => $user->name,
        'birth_date' => '1995-01-01',
        'phone_number' => '0599333444',
        'id_document_path' => 'documents/id.png',
        'good_conduct_cert_path' => 'documents/conduct.pdf',
        'completed_tasks_count' => 0,
        'average_rating' => 0.0,
        'reliability_incidents_count' => 0,
    ]);
    return [$user, $profile];
}

test('2.1 request statuses match the exact required list', function () {
    $expectedStatuses = [
        'pending_acceptance',
        'accepted',
        'assigned',
        'in_progress',
        'pending_confirmation',
        'completed',
        'provider_apologized',
        'provider_delayed',
        'no_provider_found',
        'under_review',
        'cancelled',
    ];

    expect(ServiceRequest::STATUSES)->toEqualCanonicalizing($expectedStatuses);
});

test('2.2 cancellation policy allows cancelling in pending_acceptance, accepted, and the 3 exceptions', function () {
    [$elderUser, $elderProfile] = createElderUser();

    $allowedStatuses = [
        ServiceRequest::STATUS_PENDING_ACCEPTANCE,
        ServiceRequest::STATUS_ACCEPTED,
        ServiceRequest::STATUS_NO_PROVIDER_FOUND,
        ServiceRequest::STATUS_PROVIDER_APOLOGIZED,
        ServiceRequest::STATUS_PROVIDER_DELAYED,
    ];

    foreach ($allowedStatuses as $st) {
        $req = ServiceRequest::create([
            'public_id' => '#REQ-OK-' . rand(1000, 9999),
            'elder_id' => $elderProfile->id,
            'title' => 'طلب تجريبي',
            'service_type' => 'grocery',
            'description' => 'شرح',
            'location' => 'غزة',
            'scheduled_at' => now()->addDay(),
            'status' => $st,
        ]);

        $response = $this->actingAs($elderUser)
            ->delete("/requests/{$req->id}/cancel", [
                'cancellation_reason' => 'سبب تجريبي',
            ]);

        $response->assertSessionHas('status', 'request-cancelled');
        $req->refresh();
        expect($req->status)->toBe(ServiceRequest::STATUS_CANCELLED);
    }
});

test('2.2 cancellation policy strictly forbids cancelling in assigned, in_progress, and pending_confirmation', function () {
    [$elderUser, $elderProfile] = createElderUser();
    [$providerUser, $providerProfile] = createProviderUser();

    $forbiddenStatuses = [
        ServiceRequest::STATUS_ASSIGNED,
        ServiceRequest::STATUS_IN_PROGRESS,
        ServiceRequest::STATUS_PENDING_CONFIRMATION,
    ];

    foreach ($forbiddenStatuses as $st) {
        $req = ServiceRequest::create([
            'public_id' => '#REQ-BLOCK-' . rand(1000, 9999),
            'elder_id' => $elderProfile->id,
            'provider_id' => $providerProfile->id,
            'title' => 'طلب محظور الإلغاء',
            'service_type' => 'grocery',
            'description' => 'شرح',
            'location' => 'غزة',
            'scheduled_at' => now()->addDay(),
            'status' => $st,
        ]);

        $response = $this->actingAs($elderUser)
            ->delete("/requests/{$req->id}/cancel", [
                'cancellation_reason' => 'محاولة إلغاء',
            ]);

        $response->assertStatus(403);

        $req->refresh();
        expect($req->status)->toBe($st);
    }
});

test('2.3 provider apology unassigns provider, increments incidents count, and notifies elder', function () {
    [$elderUser, $elderProfile] = createElderUser();
    [$providerUser, $providerProfile] = createProviderUser();

    $req = ServiceRequest::create([
        'public_id' => '#REQ-APOLOGY',
        'elder_id' => $elderProfile->id,
        'provider_id' => $providerProfile->id,
        'title' => 'طلب للاعتذار',
        'service_type' => 'grocery',
        'description' => 'شرح',
        'location' => 'غزة',
        'scheduled_at' => now()->addDay(),
        'status' => ServiceRequest::STATUS_ASSIGNED,
    ]);

    expect($providerProfile->reliability_incidents_count)->toBe(0);

    $response = $this->actingAs($providerUser)
        ->post("/provider/tasks/{$req->id}/apologize", [
            'apology_reason' => 'ظرف طارئ مفاجئ',
        ]);

    $response->assertSessionHas('status', 'apology-completed');

    $req->refresh();
    $providerProfile->refresh();

    expect($req->status)->toBe(ServiceRequest::STATUS_PENDING_ACCEPTANCE)
        ->and($req->incident_type)->toBe('apology')
        ->and($req->provider_id)->toBeNull()
        ->and($req->previous_provider_id)->toBe($providerProfile->id);

    expect($providerProfile->reliability_incidents_count)->toBe(1);

    // Elder received notification
    $notif = Notification::where('user_id', $elderUser->id)
        ->where('type', 'provider_apologized')
        ->first();
    expect($notif)->not->toBeNull();

    // Apologizing provider CANNOT see the request in available
    expect(ServiceRequest::availableForProvider($providerUser)->where('id', $req->id)->exists())->toBeFalse();

    // Another provider CAN see the request in available
    [$otherProviderUser] = createProviderUser();
    expect(ServiceRequest::availableForProvider($otherProviderUser)->where('id', $req->id)->exists())->toBeTrue();
});

test('2.4 delay workflow marks delayed, elder can search alternative which penalizes delayed provider', function () {
    [$elderUser, $elderProfile] = createElderUser();
    [$providerUser, $providerProfile] = createProviderUser();

    $req = ServiceRequest::create([
        'public_id' => '#REQ-DELAY',
        'elder_id' => $elderProfile->id,
        'provider_id' => $providerProfile->id,
        'title' => 'طلب متأخر',
        'service_type' => 'grocery',
        'description' => 'شرح',
        'location' => 'غزة',
        'scheduled_at' => now()->subMinutes(20),
        'status' => ServiceRequest::STATUS_ASSIGNED,
    ]);

    // Expiration processing triggers delayed status
    ServiceRequest::processScheduleExpirations();
    $req->refresh();

    expect($req->status)->toBe(ServiceRequest::STATUS_PROVIDER_DELAYED)
        ->and($req->incident_type)->toBe('delay');

    // Elder chooses alternative
    $response = $this->actingAs($elderUser)
        ->patch("/requests/{$req->id}/search-alternative", [
            'scheduled_at' => now()->addDay()->format('Y-m-d H:i:s'),
        ]);

    $response->assertSessionHas('status', 'request-reassigned');
    $req->refresh();
    $providerProfile->refresh();

    expect($req->status)->toBe(ServiceRequest::STATUS_PENDING_ACCEPTANCE)
        ->and($req->provider_id)->toBeNull();

    // Provider penalized +1
    expect($providerProfile->reliability_incidents_count)->toBe(1);
});

test('2.5 no provider found when scheduled time passes without any acceptance', function () {
    [$elderUser, $elderProfile] = createElderUser();

    $req = ServiceRequest::create([
        'public_id' => '#REQ-UNCLAIMED',
        'elder_id' => $elderProfile->id,
        'provider_id' => null,
        'title' => 'طلب لم يقبله أحد',
        'service_type' => 'grocery',
        'description' => 'شرح',
        'location' => 'غزة',
        'scheduled_at' => now()->subMinutes(10),
        'status' => ServiceRequest::STATUS_PENDING_ACCEPTANCE,
    ]);

    ServiceRequest::processScheduleExpirations();
    $req->refresh();

    expect($req->status)->toBe(ServiceRequest::STATUS_NO_PROVIDER_FOUND);

    $notif = Notification::where('user_id', $elderUser->id)
        ->where('type', 'no_provider_found')
        ->first();
    expect($notif)->not->toBeNull();
});

test('2.6 mandatory rating on confirmCompletion closes request and updates provider rating and count', function () {
    [$elderUser, $elderProfile] = createElderUser();
    [$providerUser, $providerProfile] = createProviderUser();

    $req = ServiceRequest::create([
        'public_id' => '#REQ-CONFIRM',
        'elder_id' => $elderProfile->id,
        'provider_id' => $providerProfile->id,
        'title' => 'طلب بانتظار التأكيد',
        'service_type' => 'medicine',
        'description' => 'شرح',
        'location' => 'غزة',
        'scheduled_at' => now()->subHour(),
        'status' => ServiceRequest::STATUS_PENDING_CONFIRMATION,
    ]);

    // Fails without rating
    $failResponse = $this->actingAs($elderUser)
        ->patch("/requests/{$req->id}/confirm", []);
    $failResponse->assertSessionHasErrors(['rating']);
    $req->refresh();
    expect($req->status)->toBe(ServiceRequest::STATUS_PENDING_CONFIRMATION);

    // Succeeds with rating
    $successResponse = $this->actingAs($elderUser)
        ->patch("/requests/{$req->id}/confirm", [
            'stars' => 5,
            'comment' => 'متطوع رائع ومتميز',
        ]);

    $successResponse->assertSessionHas('status', 'request-completed');

    $req->refresh();
    $providerProfile->refresh();

    expect($req->status)->toBe(ServiceRequest::STATUS_COMPLETED);
    expect($providerProfile->completed_tasks_count)->toBe(1);
    expect((float) $providerProfile->average_rating)->toBe(5.0);

    $rating = Rating::where('service_request_id', $req->id)
        ->where('rater_role', 'elder')
        ->first();
    expect($rating)->not->toBeNull()
        ->and($rating->stars)->toBe(5)
        ->and($rating->visible_to_provider)->toBeTrue();
});

test('2.7 elder can report problem to transition request to under_review and open a complaint', function () {
    [$elderUser, $elderProfile] = createElderUser();
    [$providerUser, $providerProfile] = createProviderUser();

    $req = ServiceRequest::create([
        'public_id' => '#REQ-PROBLEM',
        'elder_id' => $elderProfile->id,
        'provider_id' => $providerProfile->id,
        'title' => 'طلب فيه إشكالية',
        'service_type' => 'grocery',
        'description' => 'شرح',
        'location' => 'غزة',
        'scheduled_at' => now()->subHour(),
        'status' => ServiceRequest::STATUS_PENDING_CONFIRMATION,
    ]);

    $response = $this->actingAs($elderUser)
        ->post("/requests/{$req->id}/report-problem", [
            'problem_description' => 'لم يتم إحضار الفاتورة أو الأغراض المتفق عليها بالشكل المطلوب.',
        ]);

    $response->assertSessionHas('status', 'problem-reported');

    $req->refresh();
    expect($req->status)->toBe(ServiceRequest::STATUS_UNDER_REVIEW);

    $complaint = Complaint::where('request_id', $req->id)->first();
    expect($complaint)->not->toBeNull()
        ->and($complaint->reporter_id)->toBe($elderUser->id)
        ->and($complaint->status)->toBe('open')
        ->and($complaint->description)->toContain('لم يتم إحضار الفاتورة');
});

test('2.8 provider can report an issue to the administration without rating the elder', function () {
    [$elderUser, $elderProfile] = createElderUser();
    [$providerUser, $providerProfile] = createProviderUser();

    $req = ServiceRequest::create([
        'public_id' => '#REQ-PROVIDER-REPORT',
        'elder_id' => $elderProfile->id,
        'provider_id' => $providerProfile->id,
        'title' => 'طلب مكتمل للبلاغ',
        'service_type' => 'grocery',
        'description' => 'شرح',
        'location' => 'غزة',
        'scheduled_at' => now()->subHours(2),
        'status' => ServiceRequest::STATUS_COMPLETED,
    ]);

    $response = $this->actingAs($providerUser)
        ->post("/provider/tasks/{$req->id}/report-issue", [
            'issue_type' => 'information',
            'description' => 'العنوان المكتوب في الطلب لا يطابق موقع التنفيذ.',
        ]);

    $response->assertSessionHas('status', 'provider-issue-reported');

    expect(Complaint::where('request_id', $req->id)
        ->where('reporter_id', $providerUser->id)
        ->where('status', 'open')
        ->where('description', 'like', '%معلومات الطلب غير مطابقة%')
        ->exists())->toBeTrue();

    expect(Rating::where('service_request_id', $req->id)
        ->where('rater_role', 'provider')
        ->exists())->toBeFalse()
        ->and($req->fresh()->status)->toBe(ServiceRequest::STATUS_COMPLETED);
});

test('2.9 elder can confirm completion and rate provider after a provider issue report', function () {
    [$elderUser, $elderProfile] = createElderUser();
    [$providerUser, $providerProfile] = createProviderUser();

    $req = ServiceRequest::create([
        'public_id' => '#REQ-DUAL-RATING',
        'elder_id' => $elderProfile->id,
        'provider_id' => $providerProfile->id,
        'title' => 'طلب تقييم مزدوج',
        'service_type' => 'grocery',
        'description' => 'شرح',
        'location' => 'غزة',
        'scheduled_at' => now()->subHours(2),
        'status' => ServiceRequest::STATUS_PENDING_CONFIRMATION,
    ]);

    Complaint::create([
        'request_id' => $req->id,
        'reporter_id' => $providerUser->id,
        'description' => 'بلاغ مقدم الخدمة — تعذر التواصل.',
        'status' => 'open',
    ]);

    // Elder now confirms completion and rates provider
    $response = $this->actingAs($elderUser)
        ->patch("/requests/{$req->id}/confirm", [
            'stars' => 5,
            'comment' => 'خدمة ممتازة وسريعة بارك الله فيك',
        ]);

    $response->assertRedirect('/requests?tab=completed');
    $response->assertSessionHas('status', 'request-completed');

    $req->refresh();
    expect($req->status)->toBe(ServiceRequest::STATUS_COMPLETED);

    // Only the elder rates the provider; a provider issue report is not a rating.
    $ratings = Rating::where('service_request_id', $req->id)->get();
    expect($ratings)->toHaveCount(1);

    $elderRating = $ratings->firstWhere('rater_role', 'elder');

    expect($elderRating)->not->toBeNull()
        ->and($elderRating->stars)->toBe(5)
        ->and($elderRating->visible_to_provider)->toBeTrue();

    // Verify model relationships
    expect($req->review->id)->toBe($elderRating->id);
    expect($req->elderRating->id)->toBe($elderRating->id);
});

test('2.6 real HTTP accept workflow atomically assigns request, hides cancel button from elder, and reveals contact phone', function () {
    [$elderUser, $elderProfile] = createElderUser();
    [$providerUser, $providerProfile] = createProviderUser();

    $req = ServiceRequest::create([
        'public_id' => '#REQ-ACCEPT-LIFECYCLE',
        'elder_id' => $elderProfile->id,
        'title' => 'طلب تسوق خضار',
        'service_type' => 'grocery',
        'pricing_type' => 'volunteer',
        'timing_type' => 'scheduled',
        'gender_preference' => 'any',
        'description' => 'شراء احتياجات ضرورية',
        'location' => 'حي النصر',
        'scheduled_at' => now()->addHours(5),
        'status' => ServiceRequest::STATUS_PENDING_ACCEPTANCE,
    ]);

    // 1. قبل القبول: كبير السن يرى زر الإلغاء للطلب ولا يرى هاتف مقدم الخدمة
    $elderViewBefore = $this->actingAs($elderUser)->get('/requests');
    $elderViewBefore->assertOk();
    $elderViewBefore->assertSee("openCancelModal({$req->id}");
    $elderViewBefore->assertDontSee('تواصل مع المتطوع');
    $elderViewBefore->assertDontSee($providerProfile->phone_number);

    // 2. استدعاء مسار accept() الحقيقي عبر HTTP POST
    $acceptResponse = $this->actingAs($providerUser)
        ->post("/provider/tasks/{$req->id}/accept");

    $acceptResponse->assertRedirect(route('provider.tasks', ['tab' => 'upcoming']));
    $acceptResponse->assertSessionHas('status', 'task-accepted');

    $req->refresh();

    // التحقق من أن الحالة أصبحت assigned مباشرة مع توثيق accepted_at و assigned_at
    expect($req->status)->toBe(ServiceRequest::STATUS_ASSIGNED)
        ->and($req->provider_id)->toBe($providerProfile->id)
        ->and($req->accepted_at)->not->toBeNull()
        ->and($req->assigned_at)->not->toBeNull();

    // 3. بعد القبول مباشرة: كبير السن لا يرى زر الإلغاء، ويظهر له زر التواصل ورقم هاتف المتطوع
    $elderViewAfter = $this->actingAs($elderUser)->get('/requests');
    $elderViewAfter->assertOk();
    $elderViewAfter->assertDontSee("openCancelModal({$req->id}");
    $elderViewAfter->assertSee('تواصل مع المتطوع');
    $elderViewAfter->assertSee($providerProfile->phone_number);

    // التحقق أيضاً من منع كبير السن من الإلغاء برمجياً عبر الـ endpoint
    $cancelAttempt = $this->actingAs($elderUser)->delete("/requests/{$req->id}/cancel", [
        'cancellation_reason' => 'محاولة إلغاء بعد التوكيل',
    ]);
    $cancelAttempt->assertStatus(403);
});

test('2.7 accept() strictly enforces tier requirements and rejects unqualified providers with 403', function () {
    [$elderUser, $elderProfile] = createElderUser();
    [$tier1User, $tier1Profile] = createProviderUser();
    $tier1Profile->update(['tier' => 1]);

    // طلب مرافقة طبية يتطلب Tier 3
    $escortReq = ServiceRequest::create([
        'public_id' => '#REQ-ESCORT-TIER3',
        'elder_id' => $elderProfile->id,
        'title' => 'مرافقة إلى المستشفى',
        'service_type' => 'medical_escort',
        'pricing_type' => 'volunteer',
        'timing_type' => 'scheduled',
        'gender_preference' => 'any',
        'description' => 'مرافقة لموعد عيادة',
        'location' => 'مستشفى الشفاء',
        'scheduled_at' => now()->addHours(6),
        'status' => ServiceRequest::STATUS_PENDING_ACCEPTANCE,
    ]);

    // محاولة قبول من Tier 1 -> مرفوض بـ 403
    $t1Attempt = $this->actingAs($tier1User)->post("/provider/tasks/{$escortReq->id}/accept");
    $t1Attempt->assertStatus(403);
    expect($escortReq->fresh()->status)->toBe(ServiceRequest::STATUS_PENDING_ACCEPTANCE);

    // ترقية إلى Tier 2 ومحاولة قبول مرافقة طبية -> لا يزال مرفوضاً بـ 403
    $tier1Profile->update(['tier' => 2]);
    $tier1User->refresh();
    $t2Attempt = $this->actingAs($tier1User)->post("/provider/tasks/{$escortReq->id}/accept");
    $t2Attempt->assertStatus(403);

    // ترقية إلى Tier 3 -> يُقبل بنجاح
    $tier1Profile->update(['tier' => 3]);
    $tier1User->refresh();
    $t3Attempt = $this->actingAs($tier1User)->post("/provider/tasks/{$escortReq->id}/accept");
    $t3Attempt->assertRedirect(route('provider.tasks', ['tab' => 'upcoming']));
    expect($escortReq->fresh()->status)->toBe(ServiceRequest::STATUS_ASSIGNED);
});

test('2.8 accept() strictly enforces gender preference matching and rejects mismatch with 403', function () {
    [$elderUser, $elderProfile] = createElderUser();
    [$maleUser, $maleProfile] = createProviderUser();
    $maleUser->gender = 'male';

    // طلب يشترط مقدمة خدمة أنثى
    $femaleReq = ServiceRequest::create([
        'public_id' => '#REQ-FEMALE-ONLY',
        'elder_id' => $elderProfile->id,
        'title' => 'مساعدة منزلية خفيفة',
        'service_type' => 'grocery',
        'pricing_type' => 'volunteer',
        'timing_type' => 'scheduled',
        'gender_preference' => 'female',
        'description' => 'تسوق خضار',
        'location' => 'الرمال',
        'scheduled_at' => now()->addHours(4),
        'status' => ServiceRequest::STATUS_PENDING_ACCEPTANCE,
    ]);

    // مقدم خدمة ذكر يحاول قبول طلب يشترط أنثى -> مرفوض بـ 403
    $mismatchAttempt = $this->actingAs($maleUser)->post("/provider/tasks/{$femaleReq->id}/accept");
    $mismatchAttempt->assertStatus(403);
    expect($femaleReq->fresh()->status)->toBe(ServiceRequest::STATUS_PENDING_ACCEPTANCE);

    // أنثى تقبل الطلب -> نجاح
    [$femaleUser, $femaleProfile] = createProviderUser();
    $femaleUser->gender = 'female';
    $matchAttempt = $this->actingAs($femaleUser)->post("/provider/tasks/{$femaleReq->id}/accept");
    $matchAttempt->assertRedirect(route('provider.tasks', ['tab' => 'upcoming']));
    expect($femaleReq->fresh()->status)->toBe(ServiceRequest::STATUS_ASSIGNED);
});
