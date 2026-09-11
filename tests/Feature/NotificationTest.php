<?php

use App\Models\Notification;
use App\Models\User;

test('user can view notifications page with tabs and counts', function () {
    $user = User::factory()->create();

    Notification::create([
        'user_id' => $user->id,
        'type' => 'request_status_updated',
        'message' => 'تم قبول طلبك بنجاح',
        'is_read' => false,
    ]);

    Notification::create([
        'user_id' => $user->id,
        'type' => 'tier_upgraded',
        'message' => 'مبروك! تمت ترقيتك للمستوى الثاني',
        'is_read' => true,
    ]);

    $response = $this->actingAs($user)->get(route('notifications.index'));

    $response->assertOk();
    $response->assertSee('تم قبول طلبك بنجاح');
    $response->assertSee('مبروك! تمت ترقيتك للمستوى الثاني');
    $response->assertSee('الكل');
    $response->assertSee('غير مقروءة');
    $response->assertSee('الطلبات');
});

test('user can filter notifications by tab', function () {
    $user = User::factory()->create();

    $unreadNotif = Notification::create([
        'user_id' => $user->id,
        'type' => 'general_system',
        'message' => 'إشعار غير مقروء مهم',
        'is_read' => false,
    ]);

    $readNotif = Notification::create([
        'user_id' => $user->id,
        'type' => 'general_system',
        'message' => 'إشعار قديم مقروء',
        'is_read' => true,
    ]);

    $requestNotif = Notification::create([
        'user_id' => $user->id,
        'type' => 'provider_delayed',
        'message' => 'تأخر مقدم الخدمة عن الموعد',
        'is_read' => false,
    ]);

    // Unread tab
    $responseUnread = $this->actingAs($user)->get(route('notifications.index', ['tab' => 'unread']));
    $responseUnread->assertOk();
    $responseUnread->assertSee('إشعار غير مقروء مهم');
    $responseUnread->assertDontSee('إشعار قديم مقروء');

    // Requests tab
    $responseRequests = $this->actingAs($user)->get(route('notifications.index', ['tab' => 'requests']));
    $responseRequests->assertOk();
    $responseRequests->assertSee('تأخر مقدم الخدمة عن الموعد');
    $responseRequests->assertDontSee('إشعار غير مقروء مهم');
});

test('user can mark a single notification as read', function () {
    $user = User::factory()->create();

    $notif = Notification::create([
        'user_id' => $user->id,
        'type' => 'request_status_updated',
        'message' => 'إشعار جديد',
        'is_read' => false,
    ]);

    $response = $this->actingAs($user)->patch(route('notifications.read', $notif));

    $response->assertSessionHas('status', 'notification-read');
    expect($notif->fresh()->is_read)->toBeTrue();
});

test('user cannot mark another users notification as read', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $notif = Notification::create([
        'user_id' => $user1->id,
        'type' => 'request_status_updated',
        'message' => 'إشعار خاص بالمستخدم الأول',
        'is_read' => false,
    ]);

    $response = $this->actingAs($user2)->patch(route('notifications.read', $notif));

    $response->assertForbidden();
    expect($notif->fresh()->is_read)->toBeFalse();
});

test('user can mark all their notifications as read', function () {
    $user = User::factory()->create();

    Notification::create([
        'user_id' => $user->id,
        'type' => 'n1',
        'message' => 'إشعار 1',
        'is_read' => false,
    ]);

    Notification::create([
        'user_id' => $user->id,
        'type' => 'n2',
        'message' => 'إشعار 2',
        'is_read' => false,
    ]);

    $response = $this->actingAs($user)->post(route('notifications.mark-all-read'));

    $response->assertSessionHas('status', 'all-notifications-read');
    expect(Notification::where('user_id', $user->id)->where('is_read', false)->count())->toBe(0);
});

test('provider views notifications with provider sidebar layout', function () {
    $user = User::factory()->create([
        'status' => 'approved',
        'email_verified_at' => now(),
    ]);
    \App\Models\ServiceProviderProfile::create([
        'user_id' => $user->id,
        'full_name' => $user->name,
        'birth_date' => '1995-01-01',
        'phone_number' => '0599333444',
        'id_document_path' => 'docs/id.pdf',
        'good_conduct_cert_path' => 'docs/conduct.pdf',
        'tier' => 2,
    ]);

    $response = $this->actingAs($user)->get(route('notifications.index'));

    $response->assertOk();
    $response->assertSee('بوابة مقدم الخدمة');
    $response->assertSee('sidebar');
    $response->assertSee('مركز الإشعارات والتنبيهات');
});
