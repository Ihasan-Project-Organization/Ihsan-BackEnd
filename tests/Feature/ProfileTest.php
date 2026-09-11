<?php

use App\Models\User;

test('profile page is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/profile');

    $response->assertOk();
});

test('profile information can be updated', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $user->refresh();

    $this->assertSame('Test User', $user->name);
    $this->assertSame('test@example.com', $user->email);
    $this->assertNull($user->email_verified_at);
    // TODO: reconnect in stage 3.2/3.3 (extended profile attributes)
});

test('email verification status is unchanged when the email address is unchanged', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => 'Test User',
            'email' => $user->email,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $this->assertNotNull($user->refresh()->email_verified_at);
});

test('user can delete their account', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete('/profile', [
            'password' => 'password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
    $this->assertNull($user->fresh());
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from('/profile')
        ->delete('/profile', [
            'password' => 'wrong-password',
        ]);

    $response
        ->assertSessionHasErrorsIn('userDeletion', 'password')
        ->assertRedirect('/profile');

    $this->assertNotNull($user->fresh());
});

test('provider views profile with provider sidebar layout', function () {
    $user = User::factory()->create([
        'status' => 'approved',
        'email_verified_at' => now(),
    ]);
    \App\Models\ServiceProviderProfile::create([
        'user_id' => $user->id,
        'full_name' => $user->name,
        'birth_date' => '1995-01-01',
        'phone_number' => '0599333444',
        'id_document_path' => 'docs/id.pdf',
        'good_conduct_cert_path' => 'docs/conduct.pdf',
        'tier' => 2,
    ]);

    $response = $this->actingAs($user)->get('/profile');

    $response->assertOk();
    $response->assertSee('بوابة مقدم الخدمة');
    $response->assertSee('sidebar');
    $response->assertSee('الملف الشخصي والإعدادات');
});
