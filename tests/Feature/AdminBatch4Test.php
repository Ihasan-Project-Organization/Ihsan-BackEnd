<?php

use App\Models\Admin;
use App\Models\ServiceProviderProfile;
use App\Models\SystemSetting;
use App\Models\User;

function createTestSuperAdmin(): User
{
    $user = User::factory()->create([
        'status' => 'approved',
        'email_verified_at' => now(),
    ]);
    Admin::create([
        'user_id' => $user->id,
        'admin_level' => 'super_admin',
    ]);
    return $user;
}

function createTestRegularAdmin(): User
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

test('regular admin cannot access super admin routes (admins & settings)', function () {
    $regularAdmin = createTestRegularAdmin();

    $this->actingAs($regularAdmin)->get(route('admin.admins.index'))->assertStatus(403);
    $this->actingAs($regularAdmin)->get(route('admin.admins.create'))->assertStatus(403);
    $this->actingAs($regularAdmin)->post(route('admin.admins.store'), [])->assertStatus(403);
    $this->actingAs($regularAdmin)->get(route('admin.settings.index'))->assertStatus(403);
    $this->actingAs($regularAdmin)->post(route('admin.settings.update'), [])->assertStatus(403);
});

test('super admin can view admins list and create page', function () {
    $superAdmin = createTestSuperAdmin();
    $regularAdmin = createTestRegularAdmin();

    $response = $this->actingAs($superAdmin)->get(route('admin.admins.index'));
    $response->assertStatus(200);
    $response->assertSee($superAdmin->name);
    $response->assertSee($regularAdmin->name);

    $createResponse = $this->actingAs($superAdmin)->get(route('admin.admins.create'));
    $createResponse->assertStatus(200);
    $createResponse->assertSee('إضافة مدير نظام جديد');
});

test('super admin can store a new admin and super_admin', function () {
    $superAdmin = createTestSuperAdmin();

    // 1. Create a regular admin
    $response = $this->actingAs($superAdmin)->post(route('admin.admins.store'), [
        'name' => 'مدير تجريبي جديد',
        'email' => 'newadmin@ehsan.test',
        'password' => 'secret1234',
        'password_confirmation' => 'secret1234',
        'admin_level' => 'admin',
    ]);

    $response->assertRedirect(route('admin.admins.index'));
    $response->assertSessionHas('success');

    $newAdminUser = User::where('email', 'newadmin@ehsan.test')->first();
    expect($newAdminUser)->not->toBeNull();
    expect($newAdminUser->status)->toBe('approved');
    expect($newAdminUser->admin)->not->toBeNull();
    expect($newAdminUser->admin->admin_level)->toBe('admin');

    // 2. Create another super_admin
    $response2 = $this->actingAs($superAdmin)->post(route('admin.admins.store'), [
        'name' => 'مدير أعلى تجريبي',
        'email' => 'super2@ehsan.test',
        'password' => 'secret1234',
        'password_confirmation' => 'secret1234',
        'admin_level' => 'super_admin',
    ]);

    $response2->assertRedirect(route('admin.admins.index'));
    $super2User = User::where('email', 'super2@ehsan.test')->first();
    expect($super2User->admin->admin_level)->toBe('super_admin');
});

test('super admin cannot delete their own account', function () {
    $superAdmin = createTestSuperAdmin();
    $adminRecord = $superAdmin->admin;

    $response = $this->actingAs($superAdmin)->delete(route('admin.admins.destroy', $adminRecord));
    $response->assertSessionHas('error');

    expect(Admin::find($adminRecord->id))->not->toBeNull();
    expect(User::find($superAdmin->id))->not->toBeNull();
});

test('super admin can delete another admin account', function () {
    $superAdmin = createTestSuperAdmin();
    $otherAdmin = createTestRegularAdmin();
    $otherAdminRecord = $otherAdmin->admin;

    $response = $this->actingAs($superAdmin)->delete(route('admin.admins.destroy', $otherAdminRecord));
    $response->assertRedirect(route('admin.admins.index'));
    $response->assertSessionHas('success');

    expect(Admin::find($otherAdminRecord->id))->toBeNull();
    expect(User::find($otherAdmin->id))->toBeNull();
});

test('super admin can view settings and update tier thresholds with validation', function () {
    $superAdmin = createTestSuperAdmin();

    $response = $this->actingAs($superAdmin)->get(route('admin.settings.index'));
    $response->assertStatus(200);
    $response->assertSee('عتبات الترقية');

    // Validation failure: tier_3_tasks must be greater than tier_2_tasks
    $invalidResponse = $this->actingAs($superAdmin)->post(route('admin.settings.update'), [
        'tier_2_tasks_threshold' => 20,
        'tier_2_rating_threshold' => 4.0,
        'tier_3_tasks_threshold' => 15, // Invalid: less than tier_2
        'tier_3_rating_threshold' => 4.5,
    ]);
    $invalidResponse->assertSessionHasErrors(['tier_3_tasks_threshold']);

    // Valid update
    $validResponse = $this->actingAs($superAdmin)->post(route('admin.settings.update'), [
        'tier_2_tasks_threshold' => 15,
        'tier_2_rating_threshold' => 4.2,
        'tier_3_tasks_threshold' => 40,
        'tier_3_rating_threshold' => 4.6,
    ]);
    $validResponse->assertRedirect(route('admin.settings.index'));
    $validResponse->assertSessionHas('success');

    expect(SystemSetting::get('tier_2_tasks_threshold'))->toBe('15');
    expect(SystemSetting::get('tier_2_rating_threshold'))->toBe('4.2');
    expect(SystemSetting::get('tier_3_tasks_threshold'))->toBe('40');
    expect(SystemSetting::get('tier_3_rating_threshold'))->toBe('4.6');
});

test('updateTier dynamically respects custom system_settings thresholds', function () {
    // Set custom higher thresholds
    SystemSetting::set('tier_2_tasks_threshold', 20);
    SystemSetting::set('tier_2_rating_threshold', 4.2);
    SystemSetting::set('tier_3_tasks_threshold', 50);
    SystemSetting::set('tier_3_rating_threshold', 4.7);

    $providerUser = User::factory()->create(['status' => 'approved']);
    $profile = ServiceProviderProfile::create([
        'user_id' => $providerUser->id,
        'full_name' => 'سالم متطوع',
        'birth_date' => '1995-01-01',
        'id_document_path' => 'docs/id.pdf',
        'good_conduct_cert_path' => 'docs/cert.pdf',
        'tier' => 1,
        'completed_tasks_count' => 15, // Under old rules (10), would be Tier 2. Under new rules (20), must remain Tier 1.
        'average_rating' => 4.5,
        'reliability_incidents_count' => 0,
    ]);

    expect($profile->updateTier())->toBe(1);

    // When tasks reach 20 and rating is 4.5 >= 4.2, upgrades to Tier 2
    $profile->update(['completed_tasks_count' => 20]);
    expect($profile->updateTier())->toBe(2);
    expect($profile->fresh()->tier)->toBe(2);

    // Still under Tier 3 threshold (needs 50 tasks and 4.7 rating)
    $profile->update(['completed_tasks_count' => 40, 'average_rating' => 4.8]);
    expect($profile->updateTier())->toBe(2);

    // When reaches 50 tasks and 4.8 rating, upgrades to Tier 3
    $profile->update(['completed_tasks_count' => 50]);
    expect($profile->updateTier())->toBe(3);
    expect($profile->fresh()->tier)->toBe(3);
});
