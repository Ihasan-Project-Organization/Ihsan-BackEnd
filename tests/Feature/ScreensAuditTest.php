<?php

use App\Models\Admin;
use App\Models\ElderProfile;
use App\Models\ServiceProviderProfile;
use App\Models\User;

test('heroicons render valid svgs', function () {
    $rendered = \Illuminate\Support\Facades\Blade::render('<x-heroicon-o-home class="w-5 h-5" />');
    expect($rendered)->toContain('<svg');

    $xIconHero = \Illuminate\Support\Facades\Blade::render('<x-app-icon name="chart-pie" class="w-5 h-5" />');
    expect($xIconHero)->toContain('<svg');

    $xIconLucide = \Illuminate\Support\Facades\Blade::render('<x-app-icon name="crown" class="w-5 h-5" />');
    expect($xIconLucide)->toContain('<svg');

    $xIconTabler = \Illuminate\Support\Facades\Blade::render('<x-app-icon name="broom" class="w-5 h-5" />');
    expect($xIconTabler)->toContain('<svg');
});

test('all 25 screens render without HTTP 500 errors', function () {
    // 1. شاشات عامة
    $this->get(route('login'))->assertOk(); // شاشة 1
    $this->get(route('frontend.elderly.register'))->assertOk(); // شاشة 2
    $this->get(route('frontend.volunteer.register'))->assertOk(); // شاشة 3
    $this->get(route('auth.pending'))->assertOk(); // شاشة 4

    // 2. مستخدم كبير سن (Elder)
    $elderUser = User::factory()->create([
        'status' => 'approved',
        'email_verified_at' => now(),
    ]);
    ElderProfile::create([
        'user_id' => $elderUser->id,
        'full_name' => 'فحص كبير سن',
        'id_number' => '102030405',
        'birth_date' => '1950-01-01',
        'city' => 'غزة',
        'phone_number' => '0599000001',
    ]);

    $this->actingAs($elderUser)->get(route('dashboard'))->assertOk(); // شاشة 5 و 6 (Modal)
    $this->actingAs($elderUser)->get(route('service-requests.index'))->assertOk(); // شاشة 7 و 8 و 9 و 10
    $this->actingAs($elderUser)->get(route('profile.edit'))->assertOk(); // شاشة 17
    $this->actingAs($elderUser)->get(route('notifications.index'))->assertOk(); // شاشة 18

    // 3. مقدم خدمة (Provider)
    $providerUser = User::factory()->create([
        'status' => 'approved',
        'email_verified_at' => now(),
    ]);
    ServiceProviderProfile::create([
        'user_id' => $providerUser->id,
        'full_name' => 'فحص متطوع',
        'id_number' => '203040506',
        'birth_date' => '1998-05-15',
        'phone_number' => '0599000002',
        'id_document_path' => 'documents/id.png',
        'good_conduct_cert_path' => 'documents/conduct.pdf',
        'tier' => 1,
        'completed_tasks_count' => 5,
        'average_rating' => 4.8,
        'is_available' => true,
    ]);

    $this->actingAs($providerUser)->get(route('provider.dashboard'))->assertOk(); // شاشة 11
    $this->actingAs($providerUser)->get(route('provider.available'))->assertOk(); // شاشة 12
    $this->actingAs($providerUser)->get(route('provider.tasks'))->assertOk(); // شاشة 13
    $this->actingAs($providerUser)->get(route('provider.performance'))->assertOk(); // شاشة 14
    $this->actingAs($providerUser)->get(route('provider.certificates'))->assertOk(); // شاشة 15
    $this->actingAs($providerUser)->get(route('provider.availability'))->assertOk(); // شاشة 16

    // 4. مدير النظام الأعلى (Super Admin)
    $superAdmin = User::where('email', 'superadmin@anees.com')->first();
    if (! $superAdmin) {
        $superAdmin = User::factory()->create([
            'email' => 'superadmin@anees.com',
            'status' => 'approved',
            'email_verified_at' => now(),
        ]);
        Admin::create([
            'user_id' => $superAdmin->id,
            'admin_level' => 'super_admin',
        ]);
    }

    $this->actingAs($superAdmin)->get(route('admin.dashboard'))->assertOk(); // شاشة 19
    $this->actingAs($superAdmin)->get(route('admin.approvals.index'))->assertOk(); // شاشة 20
    $this->actingAs($superAdmin)->get(route('admin.requests.index'))->assertOk(); // شاشة 21
    $this->actingAs($superAdmin)->get(route('admin.complaints.index'))->assertOk(); // شاشة 22
    $this->actingAs($superAdmin)->get(route('admin.users.index'))->assertOk(); // شاشة 23
    $this->actingAs($superAdmin)->get(route('admin.admins.index'))->assertOk(); // شاشة 24
    $this->actingAs($superAdmin)->get(route('admin.settings.index'))->assertOk(); // شاشة 25
});
