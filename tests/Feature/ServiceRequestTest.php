<?php

use App\Models\ElderProfile;
use App\Models\ServiceProviderProfile;
use App\Models\ServiceRequest;
use App\Models\User;

test('elderly user can view service requests index page', function () {
    $user = User::factory()->create();
    ElderProfile::create([
        'user_id' => $user->id,
        'full_name' => $user->name,
        'city' => 'غزة',
    ]);

    $response = $this
        ->actingAs($user)
        ->get('/requests');

    $response->assertOk();
    $response->assertSee('طلباتي');
});

test('elderly user can create a new service request', function () {
    $user = User::factory()->create();
    $elderProfile = ElderProfile::create([
        'user_id' => $user->id,
        'full_name' => $user->name,
        'city' => 'غزة',
    ]);

    $response = $this
        ->actingAs($user)
        ->post('/requests', [
            'title' => 'شراء دواء من الصيدلية',
            'description' => 'أحتاج دواء الضغط من صيدلية الشفاء القريبة.',
            'location' => 'حي الرمال، شارع الوحدة',
            'scheduled_at' => now()->addDays(2)->format('Y-m-d H:i:s'),
        ]);

    $response->assertSessionHas('status', 'request-created');
    $response->assertRedirect(route('service-requests.index', ['tab' => 'active']));

    $this->assertDatabaseHas('requests', [
        'elder_id' => $elderProfile->id,
        'title' => 'شراء دواء من الصيدلية',
        'status' => ServiceRequest::STATUS_PENDING_ACCEPTANCE,
    ]);

    $serviceRequest = ServiceRequest::where('elder_id', $elderProfile->id)->firstOrFail();
    expect($serviceRequest->public_id)->toStartWith('#REQ-');
});

test('consecutive requests generate unique sequential public_ids without collision', function () {
    $user = User::factory()->create();
    $elderProfile = ElderProfile::create([
        'user_id' => $user->id,
        'full_name' => $user->name,
        'city' => 'غزة',
    ]);

    // Create 3 consecutive requests via POST
    for ($i = 1; $i <= 3; $i++) {
        $response = $this->actingAs($user)->post(route('service-requests.store'), [
            'title' => "طلب رقم {$i}",
            'description' => "تفاصيل الطلب {$i}",
            'location' => 'حي الرمال',
            'scheduled_at' => now()->addDays($i)->format('Y-m-d H:i:s'),
        ]);
        $response->assertSessionHas('status', 'request-created');
    }

    $requests = ServiceRequest::where('elder_id', $elderProfile->id)->get();
    expect($requests)->toHaveCount(3);

    $publicIds = $requests->pluck('public_id')->toArray();
    expect(count(array_unique($publicIds)))->toBe(3);
});

test('rescheduling request maintains public_id', function () {
    $user = User::factory()->create();
    $elderProfile = ElderProfile::create([
        'user_id' => $user->id,
        'full_name' => $user->name,
        'city' => 'غزة',
    ]);

    $provider = User::factory()->create();
    $providerProfile = ServiceProviderProfile::create([
        'user_id' => $provider->id,
        'full_name' => $provider->name,
        'birth_date' => '1995-01-01',
        'id_document_path' => 'documents/id.png',
        'good_conduct_cert_path' => 'documents/conduct.pdf',
    ]);

    $serviceRequest = ServiceRequest::create([
        'public_id' => '#REQ-1045',
        'elder_id' => $elderProfile->id,
        'provider_id' => $providerProfile->id,
        'title' => 'شراء أغراض',
        'service_type' => 'grocery',
        'description' => 'شراء بعض الاحتياجات المنزلية الأساسية.',
        'location' => 'حي النصر',
        'scheduled_at' => now()->addDay(),
        'status' => ServiceRequest::STATUS_PROVIDER_APOLOGIZED,
    ]);

    $newDate = now()->addDays(3)->format('Y-m-d H:i:s');

    $response = $this
        ->actingAs($user)
        ->patch("/requests/{$serviceRequest->id}/reschedule", [
            'scheduled_at' => $newDate,
        ]);

    $response->assertSessionHas('status', 'request-rescheduled');
    $response->assertRedirect(route('service-requests.index', ['tab' => 'active']));

    $serviceRequest->refresh();
    expect($serviceRequest->public_id)->toBe('#REQ-1045');
    expect($serviceRequest->status)->toBe(ServiceRequest::STATUS_PENDING_ACCEPTANCE);
    expect($serviceRequest->provider_id)->toBeNull();
});

test('elderly user can confirm request completion', function () {
    $user = User::factory()->create();
    $elderProfile = ElderProfile::create([
        'user_id' => $user->id,
        'full_name' => $user->name,
        'city' => 'غزة',
    ]);

    $provider = User::factory()->create();
    $providerProfile = ServiceProviderProfile::create([
        'user_id' => $provider->id,
        'full_name' => $provider->name,
        'birth_date' => '1995-01-01',
        'id_document_path' => 'documents/id.png',
        'good_conduct_cert_path' => 'documents/conduct.pdf',
    ]);

    $serviceRequest = ServiceRequest::create([
        'public_id' => '#REQ-1034',
        'elder_id' => $elderProfile->id,
        'provider_id' => $providerProfile->id,
        'title' => 'مساعدة منزلية',
        'service_type' => 'home_help',
        'description' => 'مساعدة بسيطة في ترتيب الاحتياجات.',
        'location' => 'حي الدرج',
        'scheduled_at' => now()->subHour(),
        'status' => ServiceRequest::STATUS_PENDING_CONFIRMATION,
    ]);

    $response = $this
        ->actingAs($user)
        ->patch("/requests/{$serviceRequest->id}/confirm", [
            'stars' => 5,
            'comment' => 'خدمة ممتازة جزاكم الله خيراً',
        ]);

    $response->assertSessionHas('status', 'request-completed');
    $response->assertRedirect(route('service-requests.index', ['tab' => 'completed']));

    $serviceRequest->refresh();
    expect($serviceRequest->status)->toBe(ServiceRequest::STATUS_COMPLETED);
    expect($serviceRequest->completed_at)->not->toBeNull();
});

test('elderly user can cancel a service request with a reason', function () {
    $user = User::factory()->create();
    $elderProfile = ElderProfile::create([
        'user_id' => $user->id,
        'full_name' => $user->name,
        'city' => 'غزة',
    ]);

    $serviceRequest = ServiceRequest::create([
        'public_id' => '#REQ-1021',
        'elder_id' => $elderProfile->id,
        'title' => 'زيارة ودية',
        'service_type' => 'companionship',
        'description' => 'زيارة ودية وقراءة بعض الرسائل.',
        'location' => 'حي الشيخ رضوان',
        'scheduled_at' => now()->addDays(2),
        'status' => ServiceRequest::STATUS_PENDING_ACCEPTANCE,
    ]);

    $response = $this
        ->actingAs($user)
        ->delete("/requests/{$serviceRequest->id}/cancel", [
            'cancellation_reason' => 'لم تعد الخدمة مطلوبة',
        ]);

    $response->assertSessionHas('status', 'request-cancelled');
    $response->assertRedirect(route('service-requests.index', ['tab' => 'cancelled']));

    $serviceRequest->refresh();
    expect($serviceRequest->status)->toBe(ServiceRequest::STATUS_CANCELLED);
    expect($serviceRequest->cancellation_reason)->toBe('لم تعد الخدمة مطلوبة');
});

test('elderly user can submit a review for completed service request', function () {
    $user = User::factory()->create();
    $elderProfile = ElderProfile::create([
        'user_id' => $user->id,
        'full_name' => $user->name,
        'city' => 'غزة',
    ]);

    $provider = User::factory()->create();
    $providerProfile = ServiceProviderProfile::create([
        'user_id' => $provider->id,
        'full_name' => $provider->name,
        'birth_date' => '1995-01-01',
        'id_document_path' => 'documents/id.png',
        'good_conduct_cert_path' => 'documents/conduct.pdf',
    ]);

    $serviceRequest = ServiceRequest::create([
        'public_id' => '#REQ-1011',
        'elder_id' => $elderProfile->id,
        'provider_id' => $providerProfile->id,
        'title' => 'طلب دعم',
        'service_type' => 'home_help',
        'description' => 'مساعدة في مراجعة الأوراق.',
        'location' => 'حي الرمال',
        'scheduled_at' => now()->subDay(),
        'status' => ServiceRequest::STATUS_COMPLETED,
        'completed_at' => now()->subDay(),
    ]);

    $response = $this
        ->actingAs($user)
        ->post("/requests/{$serviceRequest->id}/reviews", [
            'rating' => 5,
            'comment' => 'متطوع خلوق وسريع الاستجابة بارك الله فيه.',
        ]);

    $response->assertSessionHas('status', 'review-submitted');

    $this->assertDatabaseHas('ratings', [
        'service_request_id' => $serviceRequest->id,
        'elderly_id' => $user->id,
        'provider_id' => $provider->id,
        'stars' => 5,
        'comment' => 'متطوع خلوق وسريع الاستجابة بارك الله فيه.',
    ]);
});

test('tabs filter requests properly by status', function () {
    $user = User::factory()->create();
    $elderProfile = ElderProfile::create([
        'user_id' => $user->id,
        'full_name' => $user->name,
        'city' => 'غزة',
    ]);

    ServiceRequest::create([
        'public_id' => '#REQ-1001',
        'elder_id' => $elderProfile->id,
        'title' => 'طلب نشط',
        'service_type' => 'grocery',
        'description' => 'شرح الطلب',
        'location' => 'غزة',
        'scheduled_at' => now()->addDay(),
        'status' => ServiceRequest::STATUS_PENDING_ACCEPTANCE,
    ]);

    ServiceRequest::create([
        'public_id' => '#REQ-1002',
        'elder_id' => $elderProfile->id,
        'title' => 'طلب بحاجة لإجراء',
        'service_type' => 'grocery',
        'description' => 'شرح الطلب',
        'location' => 'غزة',
        'scheduled_at' => now()->addDay(),
        'status' => ServiceRequest::STATUS_NO_PROVIDER_FOUND,
    ]);

    $responseActive = $this->actingAs($user)->get('/requests?tab=active');
    $responseActive->assertOk();
    $responseActive->assertSee('#REQ-1001');
    $responseActive->assertDontSee('#REQ-1002');

    $responseNeedsAction = $this->actingAs($user)->get('/requests?tab=needs_action');
    $responseNeedsAction->assertOk();
    $responseNeedsAction->assertSee('#REQ-1002');
    $responseNeedsAction->assertDontSee('#REQ-1001');
});
