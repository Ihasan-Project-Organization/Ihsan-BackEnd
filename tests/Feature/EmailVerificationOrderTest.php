<?php

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;

test('new unverified user is directed to verify-email notice screen first even if status is pending', function () {
    $response = $this->post('/register', [
        'role' => 'elder',
        'name' => 'مستخدم جديد',
        'email' => 'newuser@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'phone_number' => '0599000111',
        'city' => 'غزة',
    ]);

    $user = User::where('email', 'newuser@example.com')->first();
    expect($user)->not->toBeNull()
        ->and($user->status)->toBe('pending')
        ->and($user->hasVerifiedEmail())->toBeFalse();

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect(route('verification.notice'));
});

test('unverified user attempting to access pending-approval screen directly is redirected to verify-email', function () {
    $user = User::factory()->unverified()->pending()->create();

    $response = $this->actingAs($user)->get(route('auth.pending'));

    $response->assertRedirect(route('verification.notice'));
});

test('unverified user attempting to access any protected route is redirected to verify-email', function () {
    $user = User::factory()->unverified()->pending()->create();

    $dashboardResponse = $this->actingAs($user)->get('/dashboard');
    $dashboardResponse->assertRedirect(route('verification.notice'));

    $requestsResponse = $this->actingAs($user)->get('/requests');
    $requestsResponse->assertRedirect(route('verification.notice'));
});

test('unverified user logging in is redirected to verify-email notice first, regardless of pending status', function () {
    $user = User::factory()->unverified()->pending()->create([
        'email' => 'unverified_pending@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->post('/login', [
        'email' => 'unverified_pending@example.com',
        'password' => 'password123',
    ]);

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect(route('verification.notice'));
});

test('clicking verification link marks email verified and redirects to auth.pending if status is pending', function () {
    Event::fake();

    $user = User::factory()->unverified()->pending()->create();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1($user->email)]
    );

    $response = $this->actingAs($user)->get($verificationUrl);

    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
    $response->assertRedirect(route('auth.pending'));
});

test('user with verified email but pending status is directed to pending approval screen', function () {
    $user = User::factory()->pending()->create([
        'email_verified_at' => now(),
        'email' => 'verified_pending@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->post('/login', [
        'email' => 'verified_pending@example.com',
        'password' => 'password123',
    ]);

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect(route('auth.pending'));

    // Can access pending-approval screen
    $viewResponse = $this->actingAs($user)->get(route('auth.pending'));
    $viewResponse->assertOk()->assertSee('حسابك قيد المراجعة');

    // Trying to access dashboard redirects to auth.pending
    $dashResponse = $this->actingAs($user)->get('/dashboard');
    $dashResponse->assertRedirect(route('auth.pending'));
});
