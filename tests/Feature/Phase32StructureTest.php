<?php

use App\Models\Admin;
use App\Models\Complaint;
use App\Models\ElderProfile;
use App\Models\Notification;
use App\Models\Rating;
use App\Models\RequestAttachment;
use App\Models\ServiceProviderProfile;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Models\VolunteerCertificate;

test('users table has status, rejection_reason, and profile_picture_path', function () {
    $user = User::create([
        'name' => 'فاطمة محمود',
        'email' => 'fatima@ihsan.com',
        'password' => bcrypt('password'),
        'status' => 'pending',
        'rejection_reason' => null,
        'profile_picture_path' => 'avatars/fatima.jpg',
    ]);

    expect($user->status)->toBe('pending');
    expect($user->profile_picture_path)->toBe('avatars/fatima.jpg');
});

test('elder profile can be created and linked to user and requests', function () {
    $user = User::factory()->create();
    $profile = ElderProfile::create([
        'user_id' => $user->id,
        'full_name' => 'الحاج سعيد',
        'city' => 'غزة',
        'id_document_path' => 'documents/id.png',
    ]);

    expect($user->elderProfile->id)->toBe($profile->id);
    expect($profile->user->id)->toBe($user->id);

    $request = ServiceRequest::create([
        'public_id' => '#REQ-3201',
        'elder_id' => $profile->id,
        'title' => 'طلب مساعدة',
        'service_type' => 'grocery',
        'pricing_type' => 'volunteer',
        'timing_type' => 'scheduled',
        'gender_preference' => 'any',
        'description' => 'شرح الطلب',
        'location' => 'غزة',
        'scheduled_at' => now()->addDay(),
        'status' => ServiceRequest::STATUS_PENDING_ACCEPTANCE,
    ]);

    expect($profile->serviceRequests)->toHaveCount(1);
    expect($request->elderProfile->id)->toBe($profile->id);
});

test('service provider profile has tier, incidents count, and links to requests and certificates', function () {
    $user = User::factory()->create();
    $providerProfile = ServiceProviderProfile::create([
        'user_id' => $user->id,
        'full_name' => 'خالد علي',
        'birth_date' => '1998-04-12',
        'id_document_path' => 'documents/khaled_id.png',
        'good_conduct_cert_path' => 'documents/conduct.pdf',
        'tier' => 1,
        'completed_tasks_count' => 0,
        'average_rating' => null,
        'is_available' => true,
        'reliability_incidents_count' => 0,
    ]);

    expect($user->serviceProviderProfile->id)->toBe($providerProfile->id);
    expect($providerProfile->tier)->toBe(1);
    expect($providerProfile->is_available)->toBeTrue();
    expect($providerProfile->reliability_incidents_count)->toBe(0);

    // Volunteer certificate
    $cert = VolunteerCertificate::create([
        'provider_id' => $providerProfile->id,
        'certificate_number' => 'CERT-2026-001',
        'issued_at' => now(),
    ]);

    expect($providerProfile->volunteerCertificates)->toHaveCount(1);
    expect($cert->provider->id)->toBe($providerProfile->id);
});

test('admin profile can be created and linked to user', function () {
    $user = User::factory()->create();
    $admin = Admin::create([
        'user_id' => $user->id,
        'admin_level' => 'super_admin',
    ]);

    expect($user->admin->id)->toBe($admin->id);
    expect($admin->admin_level)->toBe('super_admin');
});

test('requests table supports new columns, attachments, and complaints', function () {
    $userElder = User::factory()->create();
    $elderProfile = ElderProfile::create([
        'user_id' => $userElder->id,
        'full_name' => 'أم محمد',
        'city' => 'غزة',
    ]);

    $userProvider = User::factory()->create();
    $providerProfile = ServiceProviderProfile::create([
        'user_id' => $userProvider->id,
        'full_name' => 'أحمد حسن',
        'birth_date' => '2000-01-01',
        'id_document_path' => 'documents/ahmed_id.png',
        'good_conduct_cert_path' => 'documents/conduct.pdf',
    ]);

    $request = ServiceRequest::create([
        'public_id' => '#REQ-3202',
        'elder_id' => $elderProfile->id,
        'provider_id' => $providerProfile->id,
        'title' => 'طلب مساعدة منزلية',
        'service_type' => 'home_help',
        'pricing_type' => 'paid',
        'proposed_price' => 50.00,
        'timing_type' => 'immediate',
        'gender_preference' => 'male',
        'incident_type' => 'delay',
        'description' => 'المساعدة في حمل أغراض',
        'location' => 'حي الرمال',
        'scheduled_at' => now()->addHours(2),
        'status' => ServiceRequest::STATUS_ACCEPTED,
        'assigned_at' => now(),
    ]);

    expect($request->pricing_type)->toBe('paid');
    expect((float) $request->proposed_price)->toBe(50.0);
    expect($request->timing_type)->toBe('immediate');
    expect($request->gender_preference)->toBe('male');
    expect($request->incident_type)->toBe('delay');
    expect($request->assigned_at)->not->toBeNull();

    // Attachments
    $attachment = RequestAttachment::create([
        'request_id' => $request->id,
        'file_path' => 'attachments/order.pdf',
    ]);
    expect($request->attachments)->toHaveCount(1);
    expect($attachment->serviceRequest->id)->toBe($request->id);

    // Complaints
    $complaint = Complaint::create([
        'request_id' => $request->id,
        'reporter_id' => $userElder->id,
        'description' => 'تأخر مقدم الخدمة عن الموعد',
        'status' => 'open',
    ]);
    expect($request->complaints)->toHaveCount(1);
    expect($complaint->serviceRequest->id)->toBe($request->id);
    expect($complaint->reporter->id)->toBe($userElder->id);
});

test('ratings table supports rater_role and visible_to_provider', function () {
    $userElder = User::factory()->create();
    $elderProfile = ElderProfile::create([
        'user_id' => $userElder->id,
        'full_name' => 'أبو خالد',
        'city' => 'غزة',
    ]);

    $userProvider = User::factory()->create();
    $providerProfile = ServiceProviderProfile::create([
        'user_id' => $userProvider->id,
        'full_name' => 'طارق',
        'birth_date' => '1997-02-02',
        'id_document_path' => 'documents/tareq_id.png',
        'good_conduct_cert_path' => 'documents/conduct.pdf',
    ]);

    $request = ServiceRequest::create([
        'public_id' => '#REQ-3203',
        'elder_id' => $elderProfile->id,
        'provider_id' => $providerProfile->id,
        'title' => 'مرافقة',
        'service_type' => 'medical_escort',
        'pricing_type' => 'volunteer',
        'timing_type' => 'scheduled',
        'gender_preference' => 'any',
        'description' => 'مرافقة طبية',
        'location' => 'غزة',
        'scheduled_at' => now()->subDay(),
        'status' => ServiceRequest::STATUS_COMPLETED,
        'completed_at' => now()->subDay(),
    ]);

    $ratingElder = Rating::create([
        'service_request_id' => $request->id,
        'elderly_id' => $userElder->id,
        'provider_id' => $userProvider->id,
        'stars' => 5,
        'comment' => 'ممتاز جداً',
        'rater_role' => 'elder',
        'visible_to_provider' => true,
    ]);

    expect($ratingElder->rater_role)->toBe('elder');
    expect($ratingElder->visible_to_provider)->toBeTrue();
    expect($request->ratings)->toHaveCount(1);
});

test('notifications table links to user', function () {
    $user = User::factory()->create();
    $notification = Notification::create([
        'user_id' => $user->id,
        'type' => 'request_status_updated',
        'message' => 'تم قبول طلبك بنجاح',
        'is_read' => false,
    ]);

    expect($user->notifications)->toHaveCount(1);
    expect($notification->user->id)->toBe($user->id);
    expect($notification->is_read)->toBeFalse();
});
