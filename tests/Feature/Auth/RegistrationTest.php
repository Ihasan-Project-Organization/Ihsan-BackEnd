<?php

use App\Models\RegistrationProfile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('custom registration choice screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertOk()
        ->assertSee('إنشاء حساب جديد')
        ->assertSee(route('frontend.elderly.register'))
        ->assertSee(route('frontend.volunteer.register'));
});

test('custom registration screens can be rendered', function () {
    $this->get(route('frontend.elderly.register'))->assertOk();
    $this->get(route('frontend.volunteer.register'))->assertOk();
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'account_type' => 'elderly',
        'dob' => '1950-01-01',
        'phone' => '0590000000',
        'city' => 'Gaza',
        'address' => 'Main street',
        'housing_type' => 'apartment',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('elderly registration details are stored', function () {
    $this->post('/register', [
        'name' => 'Elderly User',
        'email' => 'elderly@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'account_type' => 'elderly',
        'dob' => '1950-01-01',
        'phone' => '0591234567',
        'city' => 'Gaza',
        'address' => 'Main street',
        'housing_type' => 'apartment',
    ])->assertRedirect(route('dashboard', absolute: false));

    $this->assertDatabaseHas('registration_profiles', [
        'phone' => '0591234567',
        'city' => 'Gaza',
        'housing_type' => 'apartment',
    ]);
});

test('volunteer details and documents are stored', function () {
    Storage::fake('public');

    $this->post('/register', [
        'name' => 'Volunteer User',
        'email' => 'volunteer@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'account_type' => 'volunteer',
        'dob' => '1995-01-01',
        'phone' => '0597654321',
        'id_number' => 'ID-12345',
        'id_document' => UploadedFile::fake()->image('identity.jpg'),
        'conduct_document' => UploadedFile::fake()->create('conduct.pdf', 100, 'application/pdf'),
    ])->assertRedirect(route('dashboard', absolute: false));

    $profile = RegistrationProfile::where('identity_number', 'ID-12345')->firstOrFail();
    Storage::disk('public')->assertExists($profile->identity_document_path);
    Storage::disk('public')->assertExists($profile->conduct_document_path);
});
