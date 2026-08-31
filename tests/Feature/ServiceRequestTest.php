<?php

use App\Models\ServiceRequest;
use App\Models\User;

test('elderly user can view service requests index page', function () {
    $user = User::factory()->create(['account_type' => 'elderly']);

    $response = $this
        ->actingAs($user)
        ->get('/requests');

    $response->assertOk();
    $response->assertSee('طلباتي');
});

test('elderly user can create a new service request with initial attempt', function () {
    $user = User::factory()->create(['account_type' => 'elderly']);

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

    $this->assertDatabaseHas('service_requests', [
        'user_id' => $user->id,
        'title' => 'شراء دواء من الصيدلية',
        'status' => ServiceRequest::STATUS_PENDING_ACCEPTANCE,
        'attempts_count' => 1,
    ]);

    $serviceRequest = ServiceRequest::where('user_id', $user->id)->firstOrFail();
    expect($serviceRequest->public_id)->toStartWith('#REQ-');
    expect($serviceRequest->attempts)->toHaveCount(1);
    expect($serviceRequest->attempts->first()->attempt_number)->toBe(1);
});

test('rescheduling request maintains public_id and creates a new attempt', function () {
    $user = User::factory()->create(['account_type' => 'elderly']);
    $provider = User::factory()->create(['account_type' => 'volunteer']);

    $serviceRequest = ServiceRequest::create([
        'public_id' => '#REQ-1045',
        'user_id' => $user->id,
        'assigned_provider_id' => $provider->id,
        'title' => 'شراء أغراض',
        'description' => 'شراء بعض الاحتياجات المنزلية الأساسية.',
        'location' => 'حي النصر',
        'scheduled_at' => now()->addDay(),
        'status' => ServiceRequest::STATUS_PROVIDER_APOLOGIZED,
        'attempts_count' => 1,
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
    expect($serviceRequest->assigned_provider_id)->toBeNull();
    expect($serviceRequest->attempts_count)->toBe(2);
    expect($serviceRequest->attempts)->toHaveCount(1);
    expect($serviceRequest->attempts->first()->attempt_number)->toBe(2);
});

test('elderly user can confirm request completion', function () {
    $user = User::factory()->create(['account_type' => 'elderly']);
    $provider = User::factory()->create(['account_type' => 'volunteer']);

    $serviceRequest = ServiceRequest::create([
        'public_id' => '#REQ-1034',
        'user_id' => $user->id,
        'assigned_provider_id' => $provider->id,
        'title' => 'مساعدة منزلية',
        'description' => 'مساعدة بسيطة في ترتيب الاحتياجات.',
        'location' => 'حي الدرج',
        'scheduled_at' => now()->subHour(),
        'status' => ServiceRequest::STATUS_PENDING_CONFIRMATION,
        'attempts_count' => 1,
    ]);

    $response = $this
        ->actingAs($user)
        ->patch("/requests/{$serviceRequest->id}/confirm");

    $response->assertSessionHas('status', 'request-completed');
    $response->assertRedirect(route('service-requests.index', ['tab' => 'completed']));

    $serviceRequest->refresh();
    expect($serviceRequest->status)->toBe(ServiceRequest::STATUS_COMPLETED);
    expect($serviceRequest->completed_at)->not->toBeNull();
});

test('elderly user can cancel a service request with a reason', function () {
    $user = User::factory()->create(['account_type' => 'elderly']);

    $serviceRequest = ServiceRequest::create([
        'public_id' => '#REQ-1021',
        'user_id' => $user->id,
        'title' => 'زيارة ودية',
        'description' => 'زيارة ودية وقراءة بعض الرسائل.',
        'location' => 'حي الشيخ رضوان',
        'scheduled_at' => now()->addDays(2),
        'status' => ServiceRequest::STATUS_PENDING_ACCEPTANCE,
        'attempts_count' => 1,
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
    $user = User::factory()->create(['account_type' => 'elderly']);
    $provider = User::factory()->create(['account_type' => 'volunteer']);

    $serviceRequest = ServiceRequest::create([
        'public_id' => '#REQ-1011',
        'user_id' => $user->id,
        'assigned_provider_id' => $provider->id,
        'title' => 'طلب دعم',
        'description' => 'مساعدة في مراجعة الأوراق.',
        'location' => 'حي الرمال',
        'scheduled_at' => now()->subDay(),
        'status' => ServiceRequest::STATUS_COMPLETED,
        'attempts_count' => 1,
        'completed_at' => now()->subDay(),
    ]);

    $response = $this
        ->actingAs($user)
        ->post("/requests/{$serviceRequest->id}/reviews", [
            'rating' => 5,
            'comment' => 'متطوع خلوق وسريع الاستجابة بارك الله فيه.',
        ]);

    $response->assertSessionHas('status', 'review-submitted');

    $this->assertDatabaseHas('service_reviews', [
        'service_request_id' => $serviceRequest->id,
        'elderly_id' => $user->id,
        'provider_id' => $provider->id,
        'rating' => 5,
        'comment' => 'متطوع خلوق وسريع الاستجابة بارك الله فيه.',
    ]);
});

test('tabs filter requests properly by status', function () {
    $user = User::factory()->create(['account_type' => 'elderly']);

    ServiceRequest::create([
        'public_id' => '#REQ-1001',
        'user_id' => $user->id,
        'title' => 'طلب نشط',
        'description' => 'شرح الطلب',
        'location' => 'غزة',
        'scheduled_at' => now()->addDay(),
        'status' => ServiceRequest::STATUS_PENDING_ACCEPTANCE,
    ]);

    ServiceRequest::create([
        'public_id' => '#REQ-1002',
        'user_id' => $user->id,
        'title' => 'طلب بحاجة لإجراء',
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


