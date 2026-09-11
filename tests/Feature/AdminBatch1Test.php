<?php

use App\Models\Admin;
use App\Models\Complaint;
use App\Models\ElderProfile;
use App\Models\Notification;
use App\Models\ServiceProviderProfile;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    //
});

function createTestAdmin(string $level = 'admin'): User
{
    $user = User::factory()->create([
        'status' => 'approved',
        'email_verified_at' => now(),
    ]);

    Admin::create([
        'user_id' => $user->id,
        'admin_level' => $level,
    ]);

    return $user;
}

test('0.1 unauthenticated user is redirected to login from admin routes', function () {
    $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    $this->get(route('admin.approvals.index'))->assertRedirect(route('login'));
});

test('0.1 non-admin user receives 403 when accessing admin routes', function () {
    $user = User::factory()->create(['status' => 'approved', 'email_verified_at' => now()]);

    $this->actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertForbidden();

    $this->actingAs($user)
        ->get(route('admin.approvals.index'))
        ->assertForbidden();
});

test('0.2 admin user can access dashboard and approvals index', function () {
    $admin = createTestAdmin();

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('لوحة الإدارة');

    $this->actingAs($admin)
        ->get(route('admin.approvals.index'))
        ->assertOk();
});

test('0.3 EnsureSuperAdmin rejects regular admin and allows super admin', function () {
    $regularAdmin = createTestAdmin('admin');
    $superAdmin = createTestAdmin('super_admin');

    $request = \Illuminate\Http\Request::create('/test-super', 'GET');
    $middleware = new \App\Http\Middleware\EnsureSuperAdmin();

    // Regular admin gets 403
    $this->actingAs($regularAdmin);
    try {
        $middleware->handle($request, function () {
            return response('OK');
        });
        $this->fail('Expected 403 HttpException was not thrown.');
    } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
        expect($e->getStatusCode())->toBe(403);
    }

    // Super admin passes
    $this->actingAs($superAdmin);
    $response = $middleware->handle($request, function () {
        return response('OK');
    });
    expect($response->getContent())->toBe('OK');
});

test('1.1 dashboard renders all 7 required statistics cards and links', function () {
    $admin = createTestAdmin();

    // Create elder
    $elderUser = User::factory()->create(['status' => 'approved', 'email_verified_at' => now()]);
    $elder = ElderProfile::create(['user_id' => $elderUser->id, 'full_name' => 'الحاج محمد', 'city' => 'غزة', 'phone_number' => '0599111222']);

    // Create provider
    $providerUser = User::factory()->create(['status' => 'approved', 'email_verified_at' => now()]);
    $provider = ServiceProviderProfile::create([
        'user_id' => $providerUser->id,
        'full_name' => 'المتطوع أحمد',
        'birth_date' => '2000-01-01',
        'phone_number' => '0599333444',
        'id_document_path' => 'documents/ids/sample.jpg',
        'good_conduct_cert_path' => 'documents/certs/sample.pdf',
        'tier' => 1,
        'completed_tasks_count' => 0,
        'average_rating' => 0,
    ]);

    // Create pending user
    User::factory()->create(['status' => 'pending', 'name' => 'مستخدم معلق']);

    // Create active request
    $activeReq = ServiceRequest::create([
        'public_id' => '#REQ-ACT-1',
        'elder_id' => $elder->id,
        'provider_id' => $provider->id,
        'service_type' => 'مرافقة طبية',
        'title' => 'طلب نشط',
        'description' => 'وصف الطلب النشط',
        'location' => 'غزة - شارع عمر المختار',
        'scheduled_at' => now()->addDay(),
        'status' => ServiceRequest::STATUS_IN_PROGRESS,
    ]);

    // Create needs-action request (provider_apologized)
    ServiceRequest::create([
        'public_id' => '#REQ-APO-1',
        'elder_id' => $elder->id,
        'service_type' => 'تسوق',
        'title' => 'طلب معتذر عنه',
        'description' => 'وصف الطلب المعتذر عنه',
        'location' => 'غزة - النصر',
        'scheduled_at' => now()->addDay(),
        'status' => 'provider_apologized',
    ]);

    // Create open complaint
    Complaint::create([
        'request_id' => $activeReq->id,
        'reporter_id' => $elderUser->id,
        'description' => 'شكوى مفتوحة للتجربة',
        'status' => 'open',
    ]);

    // Record 3 reliability incidents for provider
    for ($i = 0; $i < 3; $i++) {
        $provider->reliabilityIncidents()->create([
            'incident_type' => 'apology',
            'created_at' => now()->subDays(5),
        ]);
    }

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertOk();
    $response->assertSee('كبار السن (المستفيدون)');
    $response->assertSee('مقدمو الخدمة (المتطوعون)');
    $response->assertSee('بانتظار الاعتماد');
    $response->assertSee('الطلبات النشطة');
    $response->assertSee('بحاجة لإجراء عاجل');
    $response->assertSee('البلاغات والشكاوى المفتوحة');
    $response->assertSee('تنبيهات الموثوقية (30 يوماً)');
    $response->assertSee(route('admin.approvals.index'));
});

test('1.2 admin can list pending users and view their documents', function () {
    $admin = createTestAdmin();

    $pendingUser = User::factory()->create([
        'name' => 'خالد التجريبي',
        'email' => 'khaled@example.com',
        'status' => 'pending',
    ]);

    ServiceProviderProfile::create([
        'user_id' => $pendingUser->id,
        'full_name' => 'خالد التجريبي',
        'birth_date' => '1998-05-12',
        'phone_number' => '0599887766',
        'id_document_path' => 'documents/ids/sample_id.jpg',
        'good_conduct_cert_path' => 'documents/certificates/sample_cert.pdf',
        'tier' => 1,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.approvals.index'))
        ->assertOk()
        ->assertSee('خالد التجريبي')
        ->assertSee('khaled@example.com');

    $this->actingAs($admin)
        ->get(route('admin.approvals.show', $pendingUser))
        ->assertOk()
        ->assertSee('خالد التجريبي')
        ->assertSee('صورة الهوية الشخصية')
        ->assertSee('شهادة حسن السيرة والسلوك');
});

test('1.2 admin can approve pending user and notification is sent', function () {
    $admin = createTestAdmin();

    $pendingUser = User::factory()->create(['status' => 'pending', 'name' => 'سعيد']);

    $response = $this->actingAs($admin)->post(route('admin.approvals.approve', $pendingUser));

    $response->assertRedirect(route('admin.approvals.index'));
    $response->assertSessionHas('success');

    $pendingUser->refresh();
    expect($pendingUser->status)->toBe('approved');

    $notification = Notification::where('user_id', $pendingUser->id)
        ->where('type', 'account_approved')
        ->first();

    expect($notification)->not->toBeNull();
    expect($notification->message)->toContain('تم اعتماد حسابك بنجاح');
});

test('1.2 admin can reject pending user with mandatory rejection reason', function () {
    $admin = createTestAdmin();

    $pendingUser = User::factory()->create(['status' => 'pending', 'name' => 'فريد']);

    // Rejection without reason fails validation
    $this->actingAs($admin)
        ->post(route('admin.approvals.reject', $pendingUser), [])
        ->assertSessionHasErrors(['rejection_reason']);

    // Rejection with reason succeeds
    $response = $this->actingAs($admin)->post(route('admin.approvals.reject', $pendingUser), [
        'rejection_reason' => 'البيانات الشخصية غير مطابقة لشهادة الميلاد.',
    ]);

    $response->assertRedirect(route('admin.approvals.index'));

    $pendingUser->refresh();
    expect($pendingUser->status)->toBe('rejected')
        ->and($pendingUser->rejection_reason)->toBe('البيانات الشخصية غير مطابقة لشهادة الميلاد.');

    $notification = Notification::where('user_id', $pendingUser->id)
        ->where('type', 'account_rejected')
        ->first();

    expect($notification)->not->toBeNull();
    expect($notification->message)->toContain('البيانات الشخصية غير مطابقة لشهادة الميلاد.');
});

test('1.2 admin can request document resubmission, status stays pending and notification sent', function () {
    $admin = createTestAdmin();

    $pendingUser = User::factory()->create(['status' => 'pending', 'name' => 'نور']);

    // Resubmission request without note fails validation
    $this->actingAs($admin)
        ->post(route('admin.approvals.request-resubmission', $pendingUser), [])
        ->assertSessionHasErrors(['resubmission_note']);

    // Resubmission request with note succeeds
    $response = $this->actingAs($admin)->post(route('admin.approvals.request-resubmission', $pendingUser), [
        'resubmission_note' => 'صورة الهوية غير واضحة، يرجى إعادة مسحها ضوئياً.',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $pendingUser->refresh();
    expect($pendingUser->status)->toBe('pending') // Status MUST remain pending
        ->and($pendingUser->resubmission_note)->toBe('صورة الهوية غير واضحة، يرجى إعادة مسحها ضوئياً.');

    $notification = Notification::where('user_id', $pendingUser->id)
        ->where('type', 'documents_resubmission_requested')
        ->first();

    expect($notification)->not->toBeNull();
    expect($notification->message)->toContain('صورة الهوية غير واضحة، يرجى إعادة مسحها ضوئياً.');
});

test('1.3 protected documents route requires admin authorization and serves files from local disk', function () {
    Storage::fake('local');
    Storage::fake('public');

    // Create a dummy file on local disk
    Storage::disk('local')->put('documents/ids/test-id.png', 'dummy-id-content');

    $admin = createTestAdmin();
    $regularUser = User::factory()->create(['status' => 'approved', 'email_verified_at' => now()]);

    // Unauthenticated user -> redirected to login
    $guestResponse = $this->get(route('admin.documents.view', ['path' => 'documents/ids/test-id.png']));
    $guestResponse->assertRedirect(route('login'));

    // Regular non-admin user -> 403 Forbidden
    $nonAdminResponse = $this->actingAs($regularUser)->get(route('admin.documents.view', ['path' => 'documents/ids/test-id.png']));
    $nonAdminResponse->assertStatus(403);

    // Admin user -> 200 OK and serves file content
    $adminResponse = $this->actingAs($admin)->get(route('admin.documents.view', ['path' => 'documents/ids/test-id.png']));
    $adminResponse->assertOk();

    // Missing file -> 404 Not Found
    $notFoundResponse = $this->actingAs($admin)->get(route('admin.documents.view', ['path' => 'documents/ids/non-existent.png']));
    $notFoundResponse->assertStatus(404);
});

