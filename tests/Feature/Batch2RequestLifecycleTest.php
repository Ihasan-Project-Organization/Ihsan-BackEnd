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

    expect($req->status)->toBe(ServiceRequest::STATUS_PROVIDER_APOLOGIZED)
        ->and($req->incident_type)->toBe('apology')
        ->and($req->provider_id)->toBeNull();

    expect($providerProfile->reliability_incidents_count)->toBe(1);

    // Elder received notification
    $notif = Notification::where('user_id', $elderUser->id)
        ->where('type', 'provider_apologized')
        ->first();
    expect($notif)->not->toBeNull();

    // Elder can now reschedule request back to pending_acceptance
    $rescheduleResponse = $this->actingAs($elderUser)
        ->patch("/requests/{$req->id}/reschedule", [
            'scheduled_at' => now()->addDays(2)->format('Y-m-d H:i:s'),
        ]);

    $rescheduleResponse->assertSessionHas('status', 'request-rescheduled');
    $req->refresh();
    expect($req->status)->toBe(ServiceRequest::STATUS_PENDING_ACCEPTANCE);
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

test('2.8 provider can optionally rate elder with visible_to_provider = false', function () {
    [$elderUser, $elderProfile] = createElderUser();
    [$providerUser, $providerProfile] = createProviderUser();

    $req = ServiceRequest::create([
        'public_id' => '#REQ-RATE-ELDER',
        'elder_id' => $elderProfile->id,
        'provider_id' => $providerProfile->id,
        'title' => 'طلب مكتمل للتقييم',
        'service_type' => 'grocery',
        'description' => 'شرح',
        'location' => 'غزة',
        'scheduled_at' => now()->subHours(2),
        'status' => ServiceRequest::STATUS_COMPLETED,
    ]);

    $response = $this->actingAs($providerUser)
        ->post("/provider/tasks/{$req->id}/rate-elder", [
            'stars' => 5,
            'comment' => 'الحاج متعاون جداً والتعامل معه مريح ومحترم.',
        ]);

    $response->assertSessionHas('status', 'elder-rated');

    $rating = Rating::where('service_request_id', $req->id)
        ->where('rater_role', 'provider')
        ->first();

    expect($rating)->not->toBeNull()
        ->and($rating->stars)->toBe(5)
        ->and($rating->visible_to_provider)->toBeFalse()
        ->and($rating->elderly_id)->toBe($elderUser->id);
});
