<?php

use App\Models\User;

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

test('new users can register and are directed to verify email prompt', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
        'status' => 'pending',
    ]);
    $response->assertRedirect(route('verification.notice'));
});

// TODO: reconnect in stage 3.2/3.3 (profile documents and details storage tests)
