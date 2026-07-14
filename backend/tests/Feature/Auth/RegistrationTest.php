<?php

declare(strict_types=1);

use App\Modules\Authentication\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Role::create(['name' => 'guest', 'guard_name' => 'web']);
});

test('new users can register', function () {
    $response = $this->postJson('/auth/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => ['user' => ['id', 'name', 'email'], 'token'],
        ]);

    $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
});

test('registration requires name', function () {
    $response = $this->postJson('/auth/register', [
        'email' => 'test@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name']);
});

test('registration requires valid email', function () {
    $response = $this->postJson('/auth/register', [
        'name' => 'Test User',
        'email' => 'not-an-email',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

test('registration requires unique email', function () {
    User::factory()->create(['email' => 'existing@example.com']);

    $response = $this->postJson('/auth/register', [
        'name' => 'Test User',
        'email' => 'existing@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

test('registration requires password confirmation', function () {
    $response = $this->postJson('/auth/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'Password123!',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['password']);
});

test('registration requires minimum password length', function () {
    $response = $this->postJson('/auth/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'short',
        'password_confirmation' => 'short',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['password']);
});
