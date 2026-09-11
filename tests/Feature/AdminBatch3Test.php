<?php

use App\Models\Admin;
use App\Models\Complaint;
use App\Models\ElderProfile;
use App\Models\Notification;
use App\Models\ServiceProviderProfile;
use App\Models\ServiceRequest;
use App\Models\User;

beforeEach(function () {
    //
});

function createBatch3Admin(): User
{
    $user = User::factory()->create([
        'status' => 'approved',
        'email_verified_at' => now(),
    ]);

    Admin::create([
        'user_id' => $user->id,
        'admin_level' => 'admin',
    ]);

    return $user;
}

test('3.1 admin can list users and filter by role and status', function () {
    $admin = createBatch3Admin();

    $elderUser = User::factory()->create(['name' => 'الحاج مروان', 'email' => 'marwan@example.com', 'status' => 'approved', 'email_verified_at' => now()]);
    ElderProfile::create(['user_id' => $elderUser->id, 'full_name' => 'الحاج مروان', 'city' => 'غزة', 'phone_number' => '0599111222']);

    $providerUser = User::factory()->create(['name' => 'المتطوع كرم', 'email' => 'karam@example.com', 'status' => 'suspended', 'suspension_reason' => 'مخالفات متكررة', 'email_verified_at' => now()]);
    ServiceProviderProfile::create([
        'user_id' => $providerUser->id,
        'full_name' => 'المتطوع كرم',
        'birth_date' => '1996-03-01',
        'phone_number' => '0599222333',
        'id_document_path' => 'documents/ids/sample.jpg',
        'good_conduct_cert_path' => 'documents/certs/sample.pdf',
        'tier' => 1,
    ]);

    // 1. General list shows both
    $this->actingAs($admin)
        ->get(route('admin.users.index'))
        ->assertOk()
        ->assertSee('الحاج مروان')
        ->assertSee('المتطوع كرم');

    // 2. Filter by status=suspended
    $this->actingAs($admin)
        ->get(route('admin.users.index', ['status' => 'suspended']))
        ->assertOk()
        ->assertSee('المتطوع كرم')
        ->assertDontSee('الحاج مروان');

    // 3. Filter by role=elder
    $this->actingAs($admin)
        ->get(route('admin.users.index', ['role' => 'elder']))
        ->assertOk()
        ->assertSee('الحاج مروان')
        ->assertDontSee('المتطوع كرم');
});

test('3.2 admin can view user profile with complaints and incidents history', function () {
    $admin = createBatch3Admin();

    $providerUser = User::factory()->create(['name' => 'المتطوع عادل', 'status' => 'approved', 'email_verified_at' => now()]);
    $provider = ServiceProviderProfile::create([
        'user_id' => $providerUser->id,
        'full_name' => 'المتطوع عادل',
        'birth_date' => '1994-08-20',
        'phone_number' => '0599555666',
        'id_document_path' => 'documents/ids/sample.jpg',
        'good_conduct_cert_path' => 'documents/certs/sample.pdf',
        'tier' => 2,
    ]);

    // Reliability incident
    $provider->reliabilityIncidents()->create([
        'incident_type' => 'delay',
        'created_at' => now()->subDay(),
    ]);

    // Associated request + complaint against this provider
    $elderUser = User::factory()->create(['status' => 'approved', 'email_verified_at' => now()]);
    $elder = ElderProfile::create(['user_id' => $elderUser->id, 'full_name' => 'كبير سن', 'city' => 'غزة', 'phone_number' => '0599111222']);

    $req = ServiceRequest::create([
        'public_id' => '#REQ-USER-PROF-1',
        'elder_id' => $elder->id,
        'provider_id' => $provider->id,
        'service_type' => 'تسوق',
        'title' => 'طلب شراء خضار',
        'description' => 'وصف الطلب',
        'location' => 'غزة',
        'scheduled_at' => now()->subDays(2),
        'status' => ServiceRequest::STATUS_UNDER_REVIEW,
    ]);

    Complaint::create([
        'request_id' => $req->id,
        'reporter_id' => $elderUser->id,
        'description' => 'تأخر المتطوع عن إيصال الطلب لأكثر من ساعتين',
        'status' => 'open',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.users.show', $providerUser));

    $response->assertOk();
    $response->assertSee('المتطوع عادل');
    $response->assertSee('تأخر عن الموعد المحدد');
    $response->assertSee('تأخر المتطوع عن إيصال الطلب لأكثر من ساعتين');
});

test('3.2 admin can suspend user with mandatory reason and cannot suspend self', function () {
    $admin = createBatch3Admin();

    $user = User::factory()->create(['status' => 'approved', 'name' => 'مستخدم معاقب']);

    // Missing reason fails validation
    $this->actingAs($admin)
        ->post(route('admin.users.suspend', $user), [])
        ->assertSessionHasErrors(['suspension_reason']);

    // Valid suspension
    $response = $this->actingAs($admin)->post(route('admin.users.suspend', $user), [
        'suspension_reason' => 'سوء سلوك وتكرار إلغاء المواعيد دون إشعار.',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $user->refresh();
    expect($user->status)->toBe('suspended')
        ->and($user->suspension_reason)->toBe('سوء سلوك وتكرار إلغاء المواعيد دون إشعار.');

    $notification = Notification::where('user_id', $user->id)
        ->where('type', 'account_suspended')
        ->first();

    expect($notification)->not->toBeNull();
    expect($notification->message)->toContain('سوء سلوك وتكرار إلغاء المواعيد دون إشعار.');

    // Admin cannot suspend self
    $selfResponse = $this->actingAs($admin)->post(route('admin.users.suspend', $admin), [
        'suspension_reason' => 'تجربة إيقاف نفسي',
    ]);
    $selfResponse->assertSessionHas('error');
});

test('3.2 EnsureAccountApproved strictly forbids suspended user from accessing platform', function () {
    $suspendedUser = User::factory()->create([
        'status' => 'suspended',
        'suspension_reason' => 'مخالفة شروط الخدمة والاتفاقيات.',
        'email_verified_at' => now(),
    ]);
    ElderProfile::create(['user_id' => $suspendedUser->id, 'full_name' => 'المستخدم الموقوف', 'city' => 'غزة', 'phone_number' => '0599111222']);

    // Attempting to access elder dashboard when suspended
    $response = $this->actingAs($suspendedUser)->get(route('dashboard'));

    // Must be logged out and redirected to login with error
    $response->assertRedirect(route('login'));
    $response->assertSessionHasErrors('email');

    $this->assertGuest();
});

test('3.2 admin can reactivate suspended user and user can access platform again', function () {
    $admin = createBatch3Admin();

    $suspendedUser = User::factory()->create([
        'status' => 'suspended',
        'suspension_reason' => 'إيقاف مؤقت للتحقيق',
        'email_verified_at' => now(),
    ]);
    ElderProfile::create(['user_id' => $suspendedUser->id, 'full_name' => 'المستخدم المعاد تفعيله', 'city' => 'غزة', 'phone_number' => '0599111222']);

    // Admin reactivates user
    $response = $this->actingAs($admin)->post(route('admin.users.reactivate', $suspendedUser));

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $suspendedUser->refresh();
    expect($suspendedUser->status)->toBe('approved')
        ->and($suspendedUser->suspension_reason)->toBeNull();

    $notification = Notification::where('user_id', $suspendedUser->id)
        ->where('type', 'account_reactivated')
        ->first();
    expect($notification)->not->toBeNull();

    // Reactivated user can now access dashboard without being blocked
    $this->actingAs($suspendedUser)
        ->get(route('dashboard'))
        ->assertOk();
});
