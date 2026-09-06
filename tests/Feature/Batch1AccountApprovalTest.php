<?php

use App\Models\ElderProfile;
use App\Models\ServiceProviderProfile;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
});

test('1.1 elder registration sets status to pending and redirects to verification notice', function () {
    $response = $this->post('/register', [
        'role' => 'elder',
        'name' => 'أبو خالد',
        'email' => 'abukhalid@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'phone_number' => '0599111222',
        'city' => 'غزة',
    ]);

    $response->assertRedirect(route('verification.notice'));

    $user = User::where('email', 'abukhalid@example.com')->first();
    expect($user)->not->toBeNull()
        ->and($user->status)->toBe('pending');

    $this->assertAuthenticatedAs($user);

    $profile = ElderProfile::where('user_id', $user->id)->first();
    expect($profile)->not->toBeNull()
        ->and($profile->phone_number)->toBe('0599111222')
        ->and($profile->city)->toBe('غزة');
});

test('1.1 provider registration sets status to pending and redirects to verification notice', function () {
    $idFile = UploadedFile::fake()->create('id.pdf', 500);
    $conductFile = UploadedFile::fake()->create('conduct.pdf', 500);

    $response = $this->post('/register', [
        'role' => 'provider',
        'name' => 'سامي المتطوع',
        'email' => 'sami@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'phone_number' => '0599333444',
        'dob' => now()->subYears(20)->format('Y-m-d'),
        'id_document' => $idFile,
        'conduct_document' => $conductFile,
    ]);

    $response->assertRedirect(route('verification.notice'));

    $user = User::where('email', 'sami@example.com')->first();
    expect($user)->not->toBeNull()
        ->and($user->status)->toBe('pending');

    $this->assertAuthenticatedAs($user);

    $profile = ServiceProviderProfile::where('user_id', $user->id)->first();
    expect($profile)->not->toBeNull()
        ->and($profile->phone_number)->toBe('0599333444')
        ->and($profile->id_document_path)->not->toBeNull()
        ->and($profile->good_conduct_cert_path)->not->toBeNull();

    Storage::disk('public')->assertExists($profile->id_document_path);
    Storage::disk('public')->assertExists($profile->good_conduct_cert_path);
});

test('1.1 pending user login is allowed but strictly confined to pending-approval screen', function () {
    $user = User::factory()->pending()->create([
        'email' => 'pending_user@example.com',
        'password' => bcrypt('password123'),
    ]);

    $loginResponse = $this->post('/login', [
        'email' => 'pending_user@example.com',
        'password' => 'password123',
    ]);

    $this->assertAuthenticatedAs($user);
    $loginResponse->assertRedirect(route('auth.pending'));

    // Attempt to access protected dashboard -> redirected to auth.pending
    $dashboardResponse = $this->get('/dashboard');
    $dashboardResponse->assertRedirect(route('auth.pending'));

    // Attempt to access requests -> redirected to auth.pending
    $requestsResponse = $this->get('/requests');
    $requestsResponse->assertRedirect(route('auth.pending'));

    // Can access pending-approval screen
    $pendingViewResponse = $this->get(route('auth.pending'));
    $pendingViewResponse->assertOk()
        ->assertSee('حسابك قيد المراجعة');
});

test('1.1 rejected user cannot login and receives rejection_reason message', function () {
    $user = User::factory()->rejected('شهادة السيرة والسلوك غير واضحة')->create([
        'email' => 'rejected_user@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->post('/login', [
        'email' => 'rejected_user@example.com',
        'password' => 'password123',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors(['email']);
    
    $error = session('errors')->first('email');
    expect($error)->toContain('شهادة السيرة والسلوك غير واضحة');
});

test('1.1 approved user can login normally and access dashboard', function () {
    $user = User::factory()->create([
        'status' => 'approved',
        'email' => 'approved_user@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->post('/login', [
        'email' => 'approved_user@example.com',
        'password' => 'password123',
    ]);

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect(route('dashboard'));

    $dashboardResponse = $this->get('/dashboard');
    $dashboardResponse->assertOk();
});

test('1.2 provider age must be at least 18 full years', function () {
    $idFile = UploadedFile::fake()->create('id.pdf', 500);
    $conductFile = UploadedFile::fake()->create('conduct.pdf', 500);

    // 17 years old
    $underageResponse = $this->post('/register', [
        'role' => 'provider',
        'name' => 'متطوع قاصر',
        'email' => 'underage@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'phone_number' => '0599111333',
        'dob' => now()->subYears(17)->format('Y-m-d'),
        'id_document' => $idFile,
        'conduct_document' => $conductFile,
    ]);

    $underageResponse->assertSessionHasErrors(['dob']);
    $this->assertDatabaseMissing('users', ['email' => 'underage@example.com']);

    // Exactly 18 years old
    $adultResponse = $this->post('/register', [
        'role' => 'provider',
        'name' => 'متطوع بالغ',
        'email' => 'adult@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'phone_number' => '0599111444',
        'dob' => now()->subYears(18)->format('Y-m-d'),
        'id_document' => $idFile,
        'conduct_document' => $conductFile,
    ]);

    $adultResponse->assertSessionDoesntHaveErrors();
    $this->assertDatabaseHas('users', ['email' => 'adult@example.com']);
});

test('1.3 good conduct certificate and id document paths are saved in service_provider_profiles', function () {
    $idFile = UploadedFile::fake()->create('my_national_id.pdf', 500);
    $conductFile = UploadedFile::fake()->create('my_conduct_cert.pdf', 500);

    $this->post('/register', [
        'role' => 'provider',
        'name' => 'رامي أحمد',
        'email' => 'rami@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'phone_number' => '0599555666',
        'dob' => '2000-01-01',
        'id_document' => $idFile,
        'conduct_document' => $conductFile,
    ]);

    $user = User::where('email', 'rami@example.com')->first();
    $profile = ServiceProviderProfile::where('user_id', $user->id)->first();

    expect($profile->good_conduct_cert_path)->not->toBeEmpty()
        ->and($profile->id_document_path)->not->toBeEmpty();

    Storage::disk('public')->assertExists($profile->good_conduct_cert_path);
    Storage::disk('public')->assertExists($profile->id_document_path);
});

test('1.4 phone number column is present and required on profiles', function () {
    // Provider registration without phone number should fail
    $idFile = UploadedFile::fake()->create('id.pdf', 500);
    $conductFile = UploadedFile::fake()->create('conduct.pdf', 500);

    $response = $this->post('/register', [
        'role' => 'provider',
        'name' => 'بدون هاتف',
        'email' => 'nophone@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'dob' => '2000-01-01',
        'id_document' => $idFile,
        'conduct_document' => $conductFile,
    ]);

    $response->assertSessionHasErrors(['phone', 'phone_number']);
});

test('1.4 phone number visibility rules by request status', function () {
    $elderUser = User::factory()->create(['status' => 'approved']);
    $elder = ElderProfile::create([
        'user_id' => $elderUser->id,
        'full_name' => 'كبير السن التجريبي',
        'city' => 'غزة',
        'phone_number' => '0599000111',
    ]);

    $providerUser = User::factory()->create(['status' => 'approved']);
    $provider = ServiceProviderProfile::create([
        'user_id' => $providerUser->id,
        'full_name' => 'مقدم الخدمة التجريبي',
        'birth_date' => '1995-01-01',
        'phone_number' => '0599000222',
        'id_document_path' => 'doc.pdf',
        'good_conduct_cert_path' => 'cert.pdf',
    ]);

    $req = ServiceRequest::create([
        'public_id' => '#REQ-TEST',
        'elder_id' => $elder->id,
        'provider_id' => $provider->id,
        'title' => 'طلب تجريبي',
        'service_type' => 'grocery',
        'description' => 'شرح الطلب',
        'location' => 'غزة',
        'scheduled_at' => now()->addDay(),
        'status' => ServiceRequest::STATUS_PENDING_ACCEPTANCE,
    ]);

    // Forbidden in pending_acceptance
    expect($req->canRevealContactPhone())->toBeFalse();

    // Forbidden in accepted (before official assignment)
    $req->status = ServiceRequest::STATUS_ACCEPTED;
    expect($req->canRevealContactPhone())->toBeFalse();

    // Forbidden in cancelled
    $req->status = ServiceRequest::STATUS_CANCELLED;
    expect($req->canRevealContactPhone())->toBeFalse();

    // Allowed in assigned
    $req->status = ServiceRequest::STATUS_ASSIGNED;
    expect($req->canRevealContactPhone())->toBeTrue();

    // Allowed in in_progress
    $req->status = ServiceRequest::STATUS_IN_PROGRESS;
    expect($req->canRevealContactPhone())->toBeTrue();

    // Allowed in pending_confirmation
    $req->status = ServiceRequest::STATUS_PENDING_CONFIRMATION;
    expect($req->canRevealContactPhone())->toBeTrue();

    // Allowed in completed
    $req->status = ServiceRequest::STATUS_COMPLETED;
    expect($req->canRevealContactPhone())->toBeTrue();
});
