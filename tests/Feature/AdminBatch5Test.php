<?php

use App\Models\Admin;
use App\Models\AdminAuditLog;
use App\Models\Complaint;
use App\Models\ElderProfile;
use App\Models\ServiceProviderProfile;
use App\Models\ServiceRequest;
use App\Models\User;

function createAuditTestAdmin(string $level = 'admin'): User
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

test('5.1 access control on audit log index page', function () {
    $guestResponse = $this->get(route('admin.audit-log.index'));
    $guestResponse->assertRedirect(route('login'));

    $elderUser = User::factory()->create(['status' => 'approved', 'email_verified_at' => now()]);
    ElderProfile::create([
        'user_id' => $elderUser->id,
        'full_name' => 'كبير سن',
        'city' => 'الرياض',
        'phone_number' => '0599111222',
    ]);
    $this->actingAs($elderUser)->get(route('admin.audit-log.index'))->assertStatus(403);

    $admin = createAuditTestAdmin('admin');
    $this->actingAs($admin)->get(route('admin.audit-log.index'))->assertStatus(200);

    $superAdmin = createAuditTestAdmin('super_admin');
    $this->actingAs($superAdmin)->get(route('admin.audit-log.index'))->assertStatus(200);
});

test('5.2 approvals controller generates audit logs for approve, reject, and requestResubmission', function () {
    $admin = createAuditTestAdmin();

    // 1. Approve
    $pendingUser1 = User::factory()->create(['status' => 'pending']);
    $this->actingAs($admin)->post(route('admin.approvals.approve', $pendingUser1));
    expect(AdminAuditLog::where('action', 'approved_user')
        ->where('target_id', $pendingUser1->id)
        ->where('admin_id', $admin->id)
        ->exists())->toBeTrue();

    // 2. Reject
    $pendingUser2 = User::factory()->create(['status' => 'pending']);
    $this->actingAs($admin)->post(route('admin.approvals.reject', $pendingUser2), [
        'rejection_reason' => 'مستندات الهوية غير واضحة',
    ]);
    $rejectLog = AdminAuditLog::where('action', 'rejected_user')
        ->where('target_id', $pendingUser2->id)
        ->first();
    expect($rejectLog)->not->toBeNull();
    expect($rejectLog->reason)->toBe('مستندات الهوية غير واضحة');

    // 3. Request Resubmission
    $pendingUser3 = User::factory()->create(['status' => 'pending']);
    $this->actingAs($admin)->post(route('admin.approvals.request-resubmission', $pendingUser3), [
        'resubmission_note' => 'يرجى إعادة رفع شهادة حسن السيرة والسلوك',
    ]);
    $resubLog = AdminAuditLog::where('action', 'resubmission_requested')
        ->where('target_id', $pendingUser3->id)
        ->first();
    expect($resubLog)->not->toBeNull();
    expect($resubLog->reason)->toBe('يرجى إعادة رفع شهادة حسن السيرة والسلوك');
});

test('5.3 users controller generates audit logs for suspend and reactivate', function () {
    $admin = createAuditTestAdmin();
    $targetUser = User::factory()->create(['status' => 'approved']);

    // Suspend
    $this->actingAs($admin)->post(route('admin.users.suspend', $targetUser), [
        'suspension_reason' => 'مخالفة معايير السلوك في المنصة',
    ]);
    $suspendLog = AdminAuditLog::where('action', 'suspended_user')
        ->where('target_id', $targetUser->id)
        ->where('admin_id', $admin->id)
        ->first();
    expect($suspendLog)->not->toBeNull();
    expect($suspendLog->reason)->toBe('مخالفة معايير السلوك في المنصة');

    // Reactivate
    $this->actingAs($admin)->post(route('admin.users.reactivate', $targetUser));
    $reactivateLog = AdminAuditLog::where('action', 'reactivated_user')
        ->where('target_id', $targetUser->id)
        ->where('admin_id', $admin->id)
        ->first();
    expect($reactivateLog)->not->toBeNull();
});

test('5.4 requests controller generates audit logs on forceStatus', function () {
    $admin = createAuditTestAdmin();
    $elder = User::factory()->create(['status' => 'approved', 'email_verified_at' => now()]);
    $elderProfile = ElderProfile::create([
        'user_id' => $elder->id,
        'full_name' => 'كبير السن',
        'city' => 'الرياض',
        'phone_number' => '0599111333',
    ]);

    $req = ServiceRequest::create([
        'elder_id' => $elderProfile->id,
        'title' => 'طلب مساعدة لشراء أغراض',
        'service_type' => 'قضاء حوائج وتوصيل',
        'description' => 'طلب مساعدة لشراء أغراض',
        'location' => 'الرياض',
        'public_id' => 'REQ-AUDIT-001',
        'status' => ServiceRequest::STATUS_PENDING_ACCEPTANCE,
        'scheduled_at' => now()->addDay(),
    ]);

    $this->actingAs($admin)->post(route('admin.requests.force-status', $req), [
        'status' => ServiceRequest::STATUS_CANCELLED,
        'reason' => 'تدخل إداري لإلغاء الطلب بناءً على رغبة المستفيد هاتفياً',
    ]);

    $forceLog = AdminAuditLog::where('action', 'force_status_changed')
        ->where('target_id', $req->id)
        ->first();
    expect($forceLog)->not->toBeNull();
    expect($forceLog->metadata['new_status'])->toBe(ServiceRequest::STATUS_CANCELLED);
    expect($forceLog->reason)->toContain('تدخل إداري');
});

test('5.5 complaints controller generates audit logs on resolve', function () {
    $admin = createAuditTestAdmin();
    $reporter = User::factory()->create(['status' => 'approved', 'email_verified_at' => now()]);
    $elderProfile = ElderProfile::create([
        'user_id' => $reporter->id,
        'full_name' => 'شاكي',
        'city' => 'الرياض',
        'phone_number' => '0599111444',
    ]);

    $req = ServiceRequest::create([
        'elder_id' => $elderProfile->id,
        'title' => 'طلب مرافقة',
        'service_type' => 'مرافقة',
        'description' => 'طلب مرافقة',
        'location' => 'الرياض',
        'public_id' => 'REQ-CMP-001',
        'status' => ServiceRequest::STATUS_UNDER_REVIEW,
        'scheduled_at' => now()->subDay(),
    ]);

    $complaint = Complaint::create([
        'request_id' => $req->id,
        'reporter_id' => $reporter->id,
        'description' => 'تأخر مقدم الخدمة لأكثر من ساعة',
        'status' => 'open',
    ]);

    $this->actingAs($admin)->post(route('admin.complaints.resolve', $complaint), [
        'status' => 'closed',
        'admin_notes' => 'تم التواصل مع الطرفين وحل المشكلة ودياً',
    ]);

    $complaintLog = AdminAuditLog::where('action', 'resolved_complaint')
        ->where('target_id', $complaint->id)
        ->first();
    expect($complaintLog)->not->toBeNull();
    expect($complaintLog->metadata['status'])->toBe('closed');
    expect($complaintLog->reason)->toBe('تم التواصل مع الطرفين وحل المشكلة ودياً');
});

test('5.6 super admin actions generate audit logs for admins and settings', function () {
    $superAdmin = createAuditTestAdmin('super_admin');

    // 1. Create admin
    $this->actingAs($superAdmin)->post(route('admin.admins.store'), [
        'name' => 'مدير مضاف جديد',
        'email' => 'audit_new_admin@ehsan.test',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'admin_level' => 'admin',
    ]);
    expect(AdminAuditLog::where('action', 'created_admin')->exists())->toBeTrue();

    // 2. Delete admin
    $otherAdmin = createAuditTestAdmin('admin');
    $otherAdminRecord = $otherAdmin->admin;
    $this->actingAs($superAdmin)->delete(route('admin.admins.destroy', $otherAdminRecord));
    expect(AdminAuditLog::where('action', 'deleted_admin')->exists())->toBeTrue();

    // 3. Update settings
    $this->actingAs($superAdmin)->post(route('admin.settings.update'), [
        'tier_2_tasks_threshold' => 12,
        'tier_2_rating_threshold' => 4.1,
        'tier_3_tasks_threshold' => 35,
        'tier_3_rating_threshold' => 4.5,
    ]);
    $settingsLog = AdminAuditLog::where('action', 'updated_settings')->first();
    expect($settingsLog)->not->toBeNull();
    expect($settingsLog->metadata['tier_2_tasks_threshold'])->toBe(12);
});

test('5.7 audit log index page filters correctly by action, admin, and search', function () {
    $admin1 = createAuditTestAdmin();
    $admin2 = createAuditTestAdmin('super_admin');

    AdminAuditLog::log('approved_user', 'User', 1, 'سبب الاعتماد الأول', ['user_name' => 'خالد']);
    AdminAuditLog::log('suspended_user', 'User', 2, 'سبب التعليق لمخالفة واضحة', ['user_name' => 'عمر']);

    // Filter by action
    $responseAction = $this->actingAs($admin1)->get(route('admin.audit-log.index', ['action' => 'suspended_user']));
    $responseAction->assertStatus(200);
    $responseAction->assertSee('سبب التعليق لمخالفة واضحة');
    $responseAction->assertDontSee('سبب الاعتماد الأول');

    // Filter by search keyword
    $responseSearch = $this->actingAs($admin1)->get(route('admin.audit-log.index', ['search' => 'الاعتماد']));
    $responseSearch->assertStatus(200);
    $responseSearch->assertSee('سبب الاعتماد الأول');
    $responseSearch->assertDontSee('سبب التعليق لمخالفة واضحة');
});
