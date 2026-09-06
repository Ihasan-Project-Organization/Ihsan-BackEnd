<?php

use App\Models\ElderProfile;
use App\Models\ServiceProviderProfile;
use App\Models\User;

function makeApprovedElder(): User {
    $user = User::factory()->create([
        'status' => 'approved',
        'email_verified_at' => now(),
    ]);
    ElderProfile::create([
        'user_id' => $user->id,
        'full_name' => $user->name,
        'city' => 'غزة',
        'phone_number' => '0599111222',
    ]);
    return $user;
}

function makeApprovedProvider(): User {
    $user = User::factory()->create([
        'status' => 'approved',
        'email_verified_at' => now(),
    ]);
    ServiceProviderProfile::create([
        'user_id' => $user->id,
        'full_name' => $user->name,
        'birth_date' => '1995-01-01',
        'phone_number' => '0599333444',
        'id_document_path' => 'docs/id.pdf',
        'good_conduct_cert_path' => 'docs/conduct.pdf',
        'tier' => 1,
        'completed_tasks_count' => 0,
        'average_rating' => 0,
        'is_available' => true,
        'reliability_incidents_count' => 0,
    ]);
    return $user;
}

test('fully approved provider is forbidden (403) from directly accessing elder routes', function () {
    $provider = makeApprovedProvider();

    // Provider trying to access elder dashboard -> 403
    $this->actingAs($provider)
        ->get('/dashboard')
        ->assertForbidden();

    // Provider trying to access elder requests -> 403
    $this->actingAs($provider)
        ->get('/requests')
        ->assertForbidden();
});

test('fully approved elder is forbidden (403) from directly accessing provider routes', function () {
    $elder = makeApprovedElder();

    // Elder trying to access provider dashboard -> 403
    $this->actingAs($elder)
        ->get('/provider/dashboard')
        ->assertForbidden();

    // Elder trying to access provider tasks -> 403
    $this->actingAs($elder)
        ->get('/provider/tasks')
        ->assertForbidden();

    // Elder trying to access provider available tasks -> 403
    $this->actingAs($elder)
        ->get('/provider/available')
        ->assertForbidden();

    // Elder trying to access provider performance -> 403
    $this->actingAs($elder)
        ->get('/provider/performance')
        ->assertForbidden();

    // Elder trying to access provider availability -> 403
    $this->actingAs($elder)
        ->get('/provider/availability')
        ->assertForbidden();

    // Elder trying to access legacy volunteer tasks -> 403
    $this->actingAs($elder)
        ->get('/volunteer/tasks')
        ->assertForbidden();
});

test('login redirects provider directly to provider.dashboard and elder to dashboard', function () {
    $provider = makeApprovedProvider();
    $provider->password = bcrypt('providerpass');
    $provider->save();

    $providerResponse = $this->post('/login', [
        'email' => $provider->email,
        'password' => 'providerpass',
    ]);
    $providerResponse->assertRedirect(route('provider.dashboard'));

    $this->post('/logout');

    $elder = makeApprovedElder();
    $elder->password = bcrypt('elderpass');
    $elder->save();

    $elderResponse = $this->post('/login', [
        'email' => $elder->email,
        'password' => 'elderpass',
    ]);
    $elderResponse->assertRedirect(route('dashboard'));
});

test('navigation bar renders role-specific links and badges', function () {
    $provider = makeApprovedProvider();
    $providerResponse = $this->actingAs($provider)->get(route('provider.dashboard'));
    $providerResponse->assertOk()
        ->assertSee('مقدم خدمة متطوع')
        ->assertSee(route('provider.available'))
        ->assertSee(route('provider.tasks'));

    $elder = makeApprovedElder();
    $elderResponse = $this->actingAs($elder)->get(route('dashboard'));
    $elderResponse->assertOk()
        ->assertSee('كبير السن / مستفيد')
        ->assertSee(route('service-requests.index'))
        ->assertSee('طلب مساعدة');
});

test('navigation bar displays active tasks count for provider without query errors', function () {
    $provider = makeApprovedProvider();
    $elder = makeApprovedElder();

    \App\Models\ServiceRequest::create([
        'public_id' => '#REQ-ACTIVE-1',
        'elder_id' => $elder->elderProfile->id,
        'provider_id' => $provider->serviceProviderProfile->id,
        'title' => 'مهمة نشطة',
        'service_type' => 'grocery',
        'description' => 'وصف',
        'location' => 'غزة',
        'status' => \App\Models\ServiceRequest::STATUS_ACCEPTED,
        'scheduled_at' => now()->addHour(),
    ]);

    $response = $this->actingAs($provider)->get(route('provider.dashboard'));
    $response->assertOk();
});

test('provider can access all provider main pages without any view errors', function () {
    $provider = makeApprovedProvider();

    $this->actingAs($provider)->get(route('provider.dashboard'))->assertOk();
    $this->actingAs($provider)->get(route('provider.available'))->assertOk();
    $this->actingAs($provider)->get(route('provider.tasks'))->assertOk();
    $this->actingAs($provider)->get(route('provider.performance'))->assertOk();
    $this->actingAs($provider)->get(route('provider.certificates'))->assertOk();
    $this->actingAs($provider)->get(route('provider.availability'))->assertOk();
});

test('provider can request and view volunteer certificate', function () {
    $provider = makeApprovedProvider();
    $elder = makeApprovedElder();

    // إتمام خدمة تطوعية
    \App\Models\ServiceRequest::create([
        'public_id' => '#REQ-CERT-1',
        'elder_id' => $elder->elderProfile->id,
        'provider_id' => $provider->serviceProviderProfile->id,
        'title' => 'مهمة منجزة للتطوع',
        'service_type' => 'grocery',
        'description' => 'وصف',
        'location' => 'غزة',
        'status' => \App\Models\ServiceRequest::STATUS_COMPLETED,
        'scheduled_at' => now()->subDay(),
        'completed_at' => now()->subHours(2),
    ]);

    // طلب إصدار الشهادة
    $response = $this->actingAs($provider)->post(route('provider.certificates.request'));
    $response->assertRedirect(route('provider.certificates'));
    $response->assertSessionHas('status', 'certificate-issued');

    expect($provider->serviceProviderProfile->volunteerCertificates()->count())->toBe(1);

    // التحقق من عرض الشهادة في الصفحة
    $viewResponse = $this->actingAs($provider)->get(route('provider.certificates'));
    $viewResponse->assertOk();
    $viewResponse->assertSee('CERT-');
});
