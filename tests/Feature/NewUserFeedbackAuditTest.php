<?php

use App\Models\Admin;
use App\Models\ElderProfile;
use App\Models\ServiceProviderProfile;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('local');
    Storage::fake('public');
});

test('elderly registration accepts id_number and stores in elder profile', function () {
    $response = $this->post(route('register'), [
        'role' => 'elder',
        'name' => 'الحاج سعيد',
        'email' => 'saeed@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'id_number' => '401987654',
        'dob' => '1950-05-10',
        'city' => 'غزة',
        'address' => 'حي الرمال، بالقرب من المسجد الكبير',
        'housing_type' => 'apartment',
        'phone_number' => '0599112233',
    ]);

    $response->assertRedirect(route('verification.notice'));

    $user = User::where('email', 'saeed@example.com')->first();
    expect($user)->not->toBeNull()
        ->and($user->elderProfile)->not->toBeNull()
        ->and($user->elderProfile->id_number)->toBe('401987654')
        ->and($user->elderProfile->city)->toBe('غزة')
        ->and($user->elderProfile->address)->toBe('حي الرمال، بالقرب من المسجد الكبير')
        ->and($user->elderProfile->housing_type)->toBe('apartment');
});

test('provider registration accepts id_number and stores in service provider profile', function () {
    $idFile = UploadedFile::fake()->create('id.pdf', 500, 'application/pdf');
    $conductFile = UploadedFile::fake()->create('conduct.pdf', 500, 'application/pdf');

    $response = $this->post(route('register'), [
        'role' => 'provider',
        'name' => 'يوسف المتطوع',
        'email' => 'youssef@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'id_number' => '402123456',
        'dob' => '1998-08-20',
        'phone_number' => '0599556677',
        'id_document' => $idFile,
        'conduct_document' => $conductFile,
    ]);

    $response->assertRedirect(route('verification.notice'));

    $user = User::where('email', 'youssef@example.com')->first();
    expect($user)->not->toBeNull()
        ->and($user->serviceProviderProfile)->not->toBeNull()
        ->and($user->serviceProviderProfile->id_number)->toBe('402123456');
});

test('admin approval view shows complete elder data and does NOT show good conduct certificate for elder', function () {
    $admin = User::factory()->create(['status' => 'approved', 'email_verified_at' => now()]);
    Admin::create(['user_id' => $admin->id, 'admin_level' => 'admin']);

    $elderUser = User::factory()->create(['name' => 'الجد خليل', 'status' => 'pending', 'email_verified_at' => now()]);
    ElderProfile::create([
        'user_id' => $elderUser->id,
        'full_name' => 'الجد خليل',
        'id_number' => '409876543',
        'city' => 'خانيونس',
        'phone_number' => '0599009988',
        'address' => 'شارع البحر',
        'housing_type' => 'independent',
        'id_document_path' => 'documents/ids/elder_id.png',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.approvals.show', $elderUser));

    $response->assertOk()
        ->assertSee('الجد خليل')
        ->assertSee('409876543')
        ->assertSee('خانيونس')
        ->assertSee('شارع البحر')
        ->assertSee('منزل مستقل')
        ->assertSee('0599009988')
        ->assertSee('صورة الهوية الشخصية')
        // Must NOT see good conduct certificate or its placeholder for elder
        ->assertDontSee('شهادة حسن السيرة والسلوك');
});

test('admin approval view for provider shows all required fields', function () {
    $admin = User::factory()->create(['status' => 'approved', 'email_verified_at' => now()]);
    Admin::create(['user_id' => $admin->id, 'admin_level' => 'admin']);

    $providerUser = User::factory()->create(['name' => 'المتطوع أحمد', 'status' => 'pending', 'email_verified_at' => now()]);
    ServiceProviderProfile::create([
        'user_id' => $providerUser->id,
        'full_name' => 'المتطوع أحمد',
        'id_number' => '403112233',
        'birth_date' => '1996-03-15',
        'phone_number' => '0599443322',
        'id_document_path' => 'documents/ids/provider_id.png',
        'good_conduct_cert_path' => 'documents/certificates/conduct.pdf',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.approvals.show', $providerUser));

    $response->assertOk()
        ->assertSee('المتطوع أحمد')
        ->assertSee('403112233')
        ->assertSee('0599443322')
        ->assertSee('1996/03/15')
        ->assertSee('صورة الهوية الشخصية')
        ->assertSee('شهادة حسن السيرة والسلوك');
});

test('request withdrawn from volunteer via apology, delay, or reschedule is hidden from them but visible to other volunteers', function () {
    // 1. Create Elder
    $elderUser = User::factory()->create(['status' => 'approved', 'email_verified_at' => now()]);
    $elderProfile = ElderProfile::create([
        'user_id' => $elderUser->id,
        'full_name' => 'كبير السن أبو سالم',
        'city' => 'غزة',
        'phone_number' => '0599111222',
    ]);

    // 2. Create Volunteer 1 (assigned then apologizes)
    $prov1User = User::factory()->create(['status' => 'approved', 'email_verified_at' => now()]);
    $prov1Profile = ServiceProviderProfile::create([
        'user_id' => $prov1User->id,
        'full_name' => 'المتطوع الأول',
        'birth_date' => '1995-01-01',
        'phone_number' => '0599333444',
        'id_document_path' => 'doc1',
        'good_conduct_cert_path' => 'cert1',
    ]);

    // 3. Create Volunteer 2
    $prov2User = User::factory()->create(['status' => 'approved', 'email_verified_at' => now()]);
    $prov2Profile = ServiceProviderProfile::create([
        'user_id' => $prov2User->id,
        'full_name' => 'المتطوع الثاني',
        'birth_date' => '1996-01-01',
        'phone_number' => '0599555666',
        'id_document_path' => 'doc2',
        'good_conduct_cert_path' => 'cert2',
    ]);

    // 4. Create request assigned to Volunteer 1
    $req = ServiceRequest::create([
        'public_id' => '#REQ-TEST-REASSIGN',
        'elder_id' => $elderProfile->id,
        'provider_id' => $prov1Profile->id,
        'title' => 'شراء مستلزمات ضرورية',
        'service_type' => 'grocery',
        'description' => 'شرح الطلب',
        'location' => 'حي الصبرة',
        'scheduled_at' => now()->addDay(),
        'status' => ServiceRequest::STATUS_ASSIGNED,
    ]);

    // Volunteer 1 apologizes
    $this->actingAs($prov1User)->post("/provider/tasks/{$req->id}/apologize", [
        'apology_reason' => 'عذر طارئ',
    ]);

    $req->refresh();
    expect($req->status)->toBe(ServiceRequest::STATUS_PENDING_ACCEPTANCE)
        ->and($req->provider_id)->toBeNull()
        ->and($req->previous_provider_id)->toBe($prov1Profile->id);

    // Volunteer 1 MUST NOT see this request in available tasks
    $prov1Available = ServiceRequest::availableForProvider($prov1User)->pluck('id')->all();
    expect($prov1Available)->not->toContain($req->id);

    // Volunteer 2 MUST see this request in available tasks
    $prov2Available = ServiceRequest::availableForProvider($prov2User)->pluck('id')->all();
    expect($prov2Available)->toContain($req->id);
});

test('requests are visible to provider even when elder profile ID matches provider profile ID', function () {
    // Both profiles have id = 1 (or same numeric ID)
    $elderUser = User::factory()->create(['status' => 'approved', 'email_verified_at' => now()]);
    $elderProfile = ElderProfile::create([
        'user_id' => $elderUser->id,
        'full_name' => 'الحاج محمد',
        'city' => 'غزة',
        'phone_number' => '0599887766',
    ]);

    $providerUser = User::factory()->create(['status' => 'approved', 'email_verified_at' => now()]);
    $providerProfile = ServiceProviderProfile::create([
        'user_id' => $providerUser->id,
        'full_name' => 'المتطوع كمال',
        'birth_date' => '1995-01-01',
        'phone_number' => '0599112244',
        'id_document_path' => 'doc',
        'good_conduct_cert_path' => 'cert',
    ]);

    // Make sure $elderProfile->id and $providerProfile->id are identical or tested
    $req = ServiceRequest::create([
        'public_id' => '#REQ-ID-MATCH',
        'elder_id' => $elderProfile->id,
        'title' => 'شراء علاج',
        'service_type' => 'grocery',
        'description' => 'شرح',
        'location' => 'غزة',
        'scheduled_at' => now()->addDay(),
        'status' => ServiceRequest::STATUS_PENDING_ACCEPTANCE,
    ]);

    $available = ServiceRequest::availableForProvider($providerUser)->pluck('id')->all();
    expect($available)->toContain($req->id);
});
