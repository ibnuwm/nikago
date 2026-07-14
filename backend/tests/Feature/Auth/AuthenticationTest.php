<?php

declare(strict_types=1);

use App\Modules\Authentication\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('users can authenticate using login', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
    ]);

    $response = $this->postJson('/auth/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => ['user' => ['id', 'name', 'email'], 'token'],
        ]);

    $this->assertDatabaseHas('personal_access_tokens', [
        'tokenable_type' => User::class,
        'tokenable_id' => $user->id,
    ]);
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $response = $this->postJson('/auth/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});

test('users can not authenticate with invalid email', function () {
    $response = $this->postJson('/auth/login', [
        'email' => 'nonexistent@example.com',
        'password' => 'password',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});

test('inactive users can not login', function () {
    $user = User::factory()->inactive()->create();

    $response = $this->postJson('/auth/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});

test('users can logout', function () {
    $user = User::factory()->create();
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/auth/logout');

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Logout success.',
        ]);

    $this->assertDatabaseMissing('personal_access_tokens', [
        'tokenable_type' => User::class,
        'tokenable_id' => $user->id,
    ]);
});

test('unauthenticated users can not logout', function () {
    $response = $this->postJson('/auth/logout');

    $response->assertUnauthorized();
});
