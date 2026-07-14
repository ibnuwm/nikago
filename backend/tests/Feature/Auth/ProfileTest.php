<?php

declare(strict_types=1);

use App\Modules\Authentication\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authenticated user can get their profile', function () {
    $user = User::factory()->create();
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/auth/me');

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $user->uuid,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
            ],
        ]);
});

test('unauthenticated user can not get profile', function () {
    $response = $this->getJson('/auth/me');

    $response->assertUnauthorized();
});

test('user can update profile', function () {
    $user = User::factory()->create();
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->putJson('/auth/profile', [
            'name' => 'Updated Name',
        ]);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => [
                'user' => [
                    'name' => 'Updated Name',
                ],
            ],
        ]);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Updated Name',
    ]);
});

test('user can not update email to taken email', function () {
    User::factory()->create(['email' => 'taken@example.com']);
    $user = User::factory()->create(['email' => 'my@example.com']);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->putJson('/auth/profile', [
            'email' => 'taken@example.com',
        ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});

test('user can update password', function () {
    $user = User::factory()->create();
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->putJson('/auth/password', [
            'current_password' => 'password',
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ]);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Password updated successfully.',
        ]);
});

test('user can not update password with wrong current password', function () {
    $user = User::factory()->create();
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->putJson('/auth/password', [
            'current_password' => 'wrong-password',
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['current_password']);
});
