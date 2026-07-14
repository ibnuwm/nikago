<?php

declare(strict_types=1);

use App\Modules\Authentication\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;

uses(RefreshDatabase::class);

test('email verification requires valid signed url', function () {
    $user = User::factory()->unverified()->create();

    $response = $this->getJson('/auth/verify-email/' . $user->id . '/invalid-hash');

    $response->assertForbidden();
});

test('user can verify email with valid signed url', function () {
    $user = User::factory()->unverified()->create();

    $url = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1($user->email)]
    );

    $response = $this->getJson($url);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Email verified successfully.',
        ]);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
    ]);
});

test('user can request resend verification', function () {
    Notification::fake();

    $user = User::factory()->unverified()->create();
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/auth/resend-verification');

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Verification link sent.',
        ]);

    Notification::assertSentTo($user, VerifyEmail::class);
});

test('verified user can not resend verification', function () {
    Notification::fake();

    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/auth/resend-verification');

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});
