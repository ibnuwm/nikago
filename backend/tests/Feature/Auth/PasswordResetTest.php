<?php

declare(strict_types=1);

use App\Modules\Authentication\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;

uses(RefreshDatabase::class);

test('user can request password reset', function () {
    Notification::fake();

    $user = User::factory()->create();

    $response = $this->postJson('/auth/forgot-password', [
        'email' => $user->email,
    ]);

    $response->assertOk()
        ->assertJson([
            'success' => true,
        ]);

    Notification::assertSentTo($user, ResetPassword::class);
});

test('forgot password requires valid email', function () {
    $response = $this->postJson('/auth/forgot-password', [
        'email' => 'nonexistent@example.com',
    ]);

    // Laravel always returns success to prevent email enumeration
    $response->assertOk()
        ->assertJson([
            'success' => true,
        ]);
});

test('user can reset password with valid token', function () {
    $user = User::factory()->create();

    $token = Password::createToken($user);

    $response = $this->postJson('/auth/reset-password', [
        'token' => $token,
        'email' => $user->email,
        'password' => 'NewPassword123!',
        'password_confirmation' => 'NewPassword123!',
    ]);

    $response->assertOk()
        ->assertJson([
            'success' => true,
        ]);
});

test('reset password requires valid token', function () {
    $user = User::factory()->create();

    $response = $this->postJson('/auth/reset-password', [
        'token' => 'invalid-token',
        'email' => $user->email,
        'password' => 'NewPassword123!',
        'password_confirmation' => 'NewPassword123!',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});

test('reset password requires password confirmation', function () {
    $user = User::factory()->create();
    $token = Password::createToken($user);

    $response = $this->postJson('/auth/reset-password', [
        'token' => $token,
        'email' => $user->email,
        'password' => 'NewPassword123!',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['password']);
});
