<?php

use App\Models\Admin;
use App\Models\Complaint;
use App\Models\ElderProfile;
use App\Models\ServiceProviderProfile;
use App\Models\ServiceRequest;
use App\Models\User;

beforeEach(function () {
    //
});

function createBatch2Admin(): User
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

test('2.1 admin can list and filter all service requests', function () {
    $admin = createBatch2Admin();

    $elderUser = User::factory()->create(['status' => 'approved', 'email_verified_at' => now()]);
    $elder = ElderProfile::create(['user_id' => $elderUser->id, 'full_name' => 'الحاج صالح', 'city' => 'غزة', 'phone_number' => '0599111222']);

    $reqActive = ServiceRequest::create([
        'public_id' => '#REQ-ACT-10',
        'elder_id' => $elder->id,
        'service_type' => 'مرافقة طبية',
        'title' => 'مرافقة للمستشفى الأوروبي',
        'description' => 'وصف الطلب النشط',
        'location' => 'خان يونس',
        'scheduled_at' => now()->addDay(),
        'status' => ServiceRequest::STATUS_IN_PROGRESS,
    ]);

    $reqCompleted = ServiceRequest::create([
        'public_id' => '#REQ-CMP-20',
        'elder_id' => $elder->id,
        'service_type' => 'تسوق',
        'title' => 'شراء أدوية شهرية',
        'description' => 'وصف الطلب المكتمل',
        'location' => 'غزة',
        'scheduled_at' => now()->subDay(),
        'status' => ServiceRequest::STATUS_COMPLETED,
    ]);

    // 1. General list displays both
    $this->actingAs($admin)
        ->get(route('admin.requests.index'))
        ->assertOk()
        ->assertSee('#REQ-ACT-10')
        ->assertSee('#REQ-CMP-20');

    // 2. Filter by status=completed shows only completed
    $this->actingAs($admin)
        ->get(route('admin.requests.index', ['status' => 'completed']))
        ->assertOk()
        ->assertSee('#REQ-CMP-20')
        ->assertDontSee('#REQ-ACT-10');

    // 3. Search by title
    $this->actingAs($admin)
        ->get(route('admin.requests.index', ['search' => 'الأوروبي']))
        ->assertOk()
        ->assertSee('#REQ-ACT-10')
        ->assertDontSee('#REQ-CMP-20');
});

test('2.1 admin can view request details and see chronological timeline', function () {
    $admin = createBatch2Admin();

    $elderUser = User::factory()->create(['status' => 'approved', 'name' => 'الجد توفيق', 'email_verified_at' => now()]);
    $elder = ElderProfile::create(['user_id' => $elderUser->id, 'full_name' => 'الجد توفيق', 'city' => 'غزة', 'phone_number' => '0599111222']);

    $providerUser = User::factory()->create(['status' => 'approved', 'name' => 'المتطوع بلال', 'email_verified_at' => now()]);
    $provider = ServiceProviderProfile::create([
        'user_id' => $providerUser->id,
        'full_name' => 'المتطوع بلال',
        'birth_date' => '1995-02-10',
        'phone_number' => '0599333444',
        'id_document_path' => 'documents/ids/sample.jpg',
        'good_conduct_cert_path' => 'documents/certs/sample.pdf',
        'tier' => 2,
        'completed_tasks_count' => 12,
        'average_rating' => 4.5,
    ]);

    $serviceRequest = ServiceRequest::create([
        'public_id' => '#REQ-TIMELINE-1',
        'elder_id' => $elder->id,
        'provider_id' => $provider->id,
        'service_type' => 'مرافقة طبية',
        'title' => 'مرافقة موعد عيادة',
        'description' => 'تفاصيل الموعد والاحتياجات الخاصة',
        'location' => 'غزة - الرمال',
        'scheduled_at' => now()->addHours(6),
        'status' => ServiceRequest::STATUS_IN_PROGRESS,
        'accepted_at' => now()->subHours(5),
        'assigned_at' => now()->subHours(4),
        'started_at' => now()->subHour(),
    ]);

    $response = $this->actingAs($admin)->get(route('admin.requests.show', $serviceRequest));

    $response->assertOk();
    $response->assertSee('#REQ-TIMELINE-1');
    $response->assertSee('الجد توفيق');
    $response->assertSee('المتطوع بلال');
    // Verify timeline milestone labels
    $response->assertSee('تم إنشاء الطلب');
    $response->assertSee('تم قبول الطلب مبدئياً');
    $response->assertSee('تم إسناد المهمة رسمياً');
    $response->assertSee('بدء تنفيذ الخدمة ميدانياً');
});

test('2.1 admin can force status change on a request', function () {
    $admin = createBatch2Admin();

    $elderUser = User::factory()->create(['status' => 'approved', 'email_verified_at' => now()]);
    $elder = ElderProfile::create(['user_id' => $elderUser->id, 'full_name' => 'المستفيد', 'city' => 'غزة', 'phone_number' => '0599111222']);

    $req = ServiceRequest::create([
        'public_id' => '#REQ-FORCE-1',
        'elder_id' => $elder->id,
        'service_type' => 'تسوق',
        'title' => 'طلب قيد التعديل',
        'description' => 'وصف',
        'location' => 'غزة',
        'scheduled_at' => now()->addDay(),
        'status' => ServiceRequest::STATUS_PENDING_ACCEPTANCE,
    ]);

    $response = $this->actingAs($admin)->post(route('admin.requests.force-status', $req), [
        'status' => ServiceRequest::STATUS_CANCELLED,
        'reason' => 'إلغاء إداري بطلب المستفيد',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $req->refresh();
    expect($req->status)->toBe(ServiceRequest::STATUS_CANCELLED);
});

test('2.2 admin can list, view, and resolve complaints', function () {
    $admin = createBatch2Admin();

    $elderUser = User::factory()->create(['status' => 'approved', 'name' => 'شاكي تجريبي', 'email_verified_at' => now()]);
    $elder = ElderProfile::create(['user_id' => $elderUser->id, 'full_name' => 'شاكي تجريبي', 'city' => 'غزة', 'phone_number' => '0599111222']);

    $req = ServiceRequest::create([
        'public_id' => '#REQ-CMP-33',
        'elder_id' => $elder->id,
        'service_type' => 'تسوق',
        'title' => 'طلب مرتبط بالشكوى',
        'description' => 'وصف',
        'location' => 'غزة',
        'scheduled_at' => now()->subDay(),
        'status' => ServiceRequest::STATUS_UNDER_REVIEW,
    ]);

    $complaint = Complaint::create([
        'request_id' => $req->id,
        'reporter_id' => $elderUser->id,
        'description' => 'المتطوع لم يحضر في الموعد المحدد إطلاقاً',
        'status' => 'open',
    ]);

    // 1. Index shows complaint
    $this->actingAs($admin)
        ->get(route('admin.complaints.index'))
        ->assertOk()
        ->assertSee('شاكي تجريبي')
        ->assertSee('#REQ-CMP-33');

    // 2. Show page displays details
    $this->actingAs($admin)
        ->get(route('admin.complaints.show', $complaint))
        ->assertOk()
        ->assertSee('المتطوع لم يحضر في الموعد المحدد إطلاقاً')
        ->assertSee('#REQ-CMP-33');

    // 3. Resolve and close complaint
    $response = $this->actingAs($admin)->post(route('admin.complaints.resolve', $complaint), [
        'status' => 'closed',
        'admin_notes' => 'تم التواصل مع المتطوع وتوجيه إنذار رسمي له، وإعادة جدولة الطلب.',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $complaint->refresh();
    expect($complaint->status)->toBe('closed')
        ->and($complaint->admin_notes)->toContain('توجيه إنذار رسمي');
});

test('2.3 reliability tab displays providers with 3 or more incidents in 30 days', function () {
    $admin = createBatch2Admin();

    // Provider 1: 3 incidents in last 30 days (FLAGGED)
    $flaggedUser = User::factory()->create(['status' => 'approved', 'name' => 'متطوع مخالف', 'email' => 'bad_provider@example.com', 'email_verified_at' => now()]);
    $flaggedProvider = ServiceProviderProfile::create([
        'user_id' => $flaggedUser->id,
        'full_name' => 'متطوع مخالف',
        'birth_date' => '1997-04-15',
        'phone_number' => '0599000111',
        'id_document_path' => 'documents/ids/sample.jpg',
        'good_conduct_cert_path' => 'documents/certs/sample.pdf',
        'tier' => 1,
        'completed_tasks_count' => 5,
        'average_rating' => 3.2,
    ]);

    for ($i = 0; $i < 3; $i++) {
        $flaggedProvider->reliabilityIncidents()->create([
            'incident_type' => $i === 0 ? 'apology' : 'delay',
            'created_at' => now()->subDays(rand(1, 15)),
        ]);
    }

    // Provider 2: only 1 incident (NOT FLAGGED)
    $goodUser = User::factory()->create(['status' => 'approved', 'name' => 'متطوع ملتزم', 'email' => 'good_provider@example.com', 'email_verified_at' => now()]);
    $goodProvider = ServiceProviderProfile::create([
        'user_id' => $goodUser->id,
        'full_name' => 'متطوع ملتزم',
        'birth_date' => '1998-01-01',
        'phone_number' => '0599000222',
        'id_document_path' => 'documents/ids/sample.jpg',
        'good_conduct_cert_path' => 'documents/certs/sample.pdf',
        'tier' => 2,
        'completed_tasks_count' => 15,
        'average_rating' => 4.8,
    ]);
    $goodProvider->reliabilityIncidents()->create([
        'incident_type' => 'delay',
        'created_at' => now()->subDays(2),
    ]);

    // Request the reliability tab
    $response = $this->actingAs($admin)->get(route('admin.complaints.index', ['tab' => 'reliability']));

    $response->assertOk();
    $response->assertSee('متطوع مخالف');
    $response->assertSee('3 حوادث موثوقية بآخر 30 يوماً');
    $response->assertSee('3.2'); // Average rating displayed
    $response->assertDontSee('متطوع ملتزم'); // Good provider must not appear in flagged list
});
