<?php

use App\Models\Admin;
use App\Models\ElderProfile;
use App\Models\Notification;
use App\Models\ProviderReliabilityIncident;
use App\Models\Rating;
use App\Models\ServiceProviderProfile;
use App\Models\ServiceRequest;
use App\Models\User;

test('3.1 provider starts at tier 1 and upgrades to tier 2 at 10 tasks and 4.0+ rating', function () {
    $user = User::factory()->create(['status' => 'approved']);
    $profile = ServiceProviderProfile::create([
        'user_id' => $user->id,
        'full_name' => 'أحمد المتطوع',
        'birth_date' => '2000-01-01',
        'id_document_path' => 'docs/id.pdf',
        'good_conduct_cert_path' => 'docs/conduct.pdf',
        'tier' => 1,
        'completed_tasks_count' => 9,
        'average_rating' => 4.5,
        'reliability_incidents_count' => 0,
    ]);

    expect($profile->updateTier())->toBe(1);

    // Now reaches 10 tasks
    $profile->update(['completed_tasks_count' => 10]);
    expect($profile->updateTier())->toBe(2);
    expect($profile->fresh()->tier)->toBe(2);

    // Check upgrade notification sent to provider
    expect(Notification::where('user_id', $user->id)
        ->where('type', 'tier_upgraded')
        ->exists())->toBeTrue();
});

test('3.1 provider upgrades to tier 3 at 30 tasks and 4.3+ rating', function () {
    $user = User::factory()->create(['status' => 'approved']);
    $profile = ServiceProviderProfile::create([
        'user_id' => $user->id,
        'full_name' => 'خالد المتميز',
        'birth_date' => '1998-05-05',
        'id_document_path' => 'docs/id.pdf',
        'good_conduct_cert_path' => 'docs/conduct.pdf',
        'tier' => 2,
        'completed_tasks_count' => 29,
        'average_rating' => 4.4,
        'reliability_incidents_count' => 0,
    ]);

    expect($profile->updateTier())->toBe(2);

    $profile->update(['completed_tasks_count' => 30]);
    expect($profile->updateTier())->toBe(3);
    expect($profile->fresh()->tier)->toBe(3);
});

test('3.1 provider is downgraded if average rating drops below tier thresholds', function () {
    $user = User::factory()->create(['status' => 'approved']);
    $profile = ServiceProviderProfile::create([
        'user_id' => $user->id,
        'full_name' => 'محمود',
        'birth_date' => '1997-01-01',
        'id_document_path' => 'docs/id.pdf',
        'good_conduct_cert_path' => 'docs/conduct.pdf',
        'tier' => 3,
        'completed_tasks_count' => 35,
        'average_rating' => 4.5,
        'reliability_incidents_count' => 0,
    ]);

    // Rating drops to 4.1 -> should downgrade from Tier 3 to Tier 2
    $profile->update(['average_rating' => 4.1]);
    expect($profile->updateTier())->toBe(2);
    expect($profile->fresh()->tier)->toBe(2);

    // Rating drops to 3.8 -> should downgrade from Tier 2 to Tier 1
    $profile->update(['average_rating' => 3.8]);
    expect($profile->updateTier())->toBe(1);
    expect($profile->fresh()->tier)->toBe(1);

    // Check downgrade notification sent
    expect(Notification::where('user_id', $user->id)
        ->where('type', 'tier_downgraded')
        ->count())->toBe(2);
});

test('3.1 completing a request automatically updates provider tier', function () {
    $elderUser = User::factory()->create(['status' => 'approved']);
    $elder = ElderProfile::create([
        'user_id' => $elderUser->id,
        'full_name' => 'الحاج صالح',
        'city' => 'القدس',
    ]);

    $providerUser = User::factory()->create(['status' => 'approved']);
    $provider = ServiceProviderProfile::create([
        'user_id' => $providerUser->id,
        'full_name' => 'سعيد',
        'birth_date' => '2001-01-01',
        'id_document_path' => 'docs/id.pdf',
        'good_conduct_cert_path' => 'docs/conduct.pdf',
        'tier' => 1,
        'completed_tasks_count' => 9,
        'average_rating' => 5.0,
        'reliability_incidents_count' => 0,
    ]);

    $request = ServiceRequest::create([
        'public_id' => 'REQ-301',
        'elder_id' => $elder->id,
        'provider_id' => $provider->id,
        'title' => 'مهمة الترقية',
        'description' => 'وصف المهمة',
        'location' => 'القدس',
        'service_type' => 'medical',
        'status' => ServiceRequest::STATUS_PENDING_CONFIRMATION,
        'scheduled_at' => now()->subHour(),
    ]);

    $response = $this->actingAs($elderUser)->patch("/requests/{$request->id}/confirm", [
        'stars' => 5,
        'comment' => 'رائع وممتاز',
    ]);

    $response->assertSessionHas('status', 'request-completed');
    $provider->refresh();

    // Now has 10 tasks and 5.0 rating -> Tier 2
    expect($provider->completed_tasks_count)->toBe(10)
        ->and($provider->tier)->toBe(2);
});

test('3.2 provider apology records incident and notifies admins if 3 incidents occur in 30 days', function () {
    $adminUser = User::factory()->create(['status' => 'approved']);
    Admin::create(['user_id' => $adminUser->id, 'admin_level' => 'super_admin']);

    $elderUser = User::factory()->create(['status' => 'approved']);
    $elder = ElderProfile::create([
        'user_id' => $elderUser->id,
        'full_name' => 'الجد توفيق',
        'city' => 'رام الله',
    ]);

    $providerUser = User::factory()->create(['status' => 'approved']);
    $provider = ServiceProviderProfile::create([
        'user_id' => $providerUser->id,
        'full_name' => 'يوسف',
        'birth_date' => '2000-02-02',
        'id_document_path' => 'docs/id.pdf',
        'good_conduct_cert_path' => 'docs/conduct.pdf',
        'tier' => 1,
        'completed_tasks_count' => 5,
        'average_rating' => 4.5,
        'reliability_incidents_count' => 0,
    ]);

    // Create 2 existing incidents in last 30 days
    ProviderReliabilityIncident::create([
        'provider_id' => $provider->id,
        'incident_type' => 'apology',
        'created_at' => now()->subDays(5),
    ]);
    ProviderReliabilityIncident::create([
        'provider_id' => $provider->id,
        'incident_type' => 'delay',
        'created_at' => now()->subDays(10),
    ]);
    $provider->update(['reliability_incidents_count' => 2]);

    $request = ServiceRequest::create([
        'public_id' => 'REQ-302',
        'elder_id' => $elder->id,
        'provider_id' => $provider->id,
        'title' => 'طلب سيتم الاعتذار عنه',
        'description' => 'وصف',
        'service_type' => 'medical',
        'location' => 'رام الله',
        'status' => ServiceRequest::STATUS_ASSIGNED,
        'scheduled_at' => now()->addDay(),
    ]);

    // Provider apologizes
    $response = $this->actingAs($providerUser)->post(route('provider.tasks.apologize', $request), [
        'apology_reason' => 'ظرف طارئ جداً',
    ]);

    $response->assertRedirect();
    $provider->refresh();

    expect($provider->reliability_incidents_count)->toBe(3);

    // Incident was saved
    expect(ProviderReliabilityIncident::where('provider_id', $provider->id)
        ->where('incident_type', 'apology')
        ->count())->toBe(2);

    // Admin received reliability alert notification
    expect(Notification::where('user_id', $adminUser->id)
        ->where('type', 'provider_reliability_alert')
        ->exists())->toBeTrue();
});

test('3.2 incidents older than 30 days do not trigger admin reliability alert', function () {
    $adminUser = User::factory()->create(['status' => 'approved']);
    Admin::create(['user_id' => $adminUser->id, 'admin_level' => 'super_admin']);

    $providerUser = User::factory()->create(['status' => 'approved']);
    $provider = ServiceProviderProfile::create([
        'user_id' => $providerUser->id,
        'full_name' => 'طارق',
        'birth_date' => '2000-03-03',
        'id_document_path' => 'docs/id.pdf',
        'good_conduct_cert_path' => 'docs/conduct.pdf',
        'tier' => 1,
        'completed_tasks_count' => 5,
        'average_rating' => 4.5,
        'reliability_incidents_count' => 2,
    ]);

    // 2 incidents older than 30 days
    $oldIncident1 = ProviderReliabilityIncident::create([
        'provider_id' => $provider->id,
        'incident_type' => 'apology',
    ]);
    $oldIncident1->created_at = now()->subDays(40);
    $oldIncident1->save();

    $oldIncident2 = ProviderReliabilityIncident::create([
        'provider_id' => $provider->id,
        'incident_type' => 'delay',
    ]);
    $oldIncident2->created_at = now()->subDays(45);
    $oldIncident2->save();

    // Now record 1 new incident today (total count is 3, but in last 30 days only 1)
    $provider->recordReliabilityIncident('apology');

    // Should NOT trigger admin notification because recent 30-day count is only 1
    expect(Notification::where('user_id', $adminUser->id)
        ->where('type', 'provider_reliability_alert')
        ->exists())->toBeFalse();
});
