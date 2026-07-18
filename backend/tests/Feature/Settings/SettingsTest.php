<?php

declare(strict_types=1);

use App\Modules\Authentication\Models\User;
use App\Modules\System\Models\ApiKey;
use App\Modules\System\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('unauthenticated user cannot access settings', function () {
    $response = $this->getJson('/api/settings/profile');
    $response->assertUnauthorized();

    $response = $this->putJson('/api/settings/profile', []);
    $response->assertUnauthorized();

    $response = $this->getJson('/api/settings/account');
    $response->assertUnauthorized();

    $response = $this->putJson('/api/settings/password', []);
    $response->assertUnauthorized();

    $response = $this->getJson('/api/settings/preferences');
    $response->assertUnauthorized();

    $response = $this->getJson('/api/settings/notifications');
    $response->assertUnauthorized();

    $response = $this->getJson('/api/settings/api-keys');
    $response->assertUnauthorized();
});

test('authenticated user can get profile', function () {
    $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/settings/profile');

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => ['user' => ['id', 'name', 'email', 'phone', 'avatar', 'status']],
        ]);
});

test('authenticated user can update profile', function () {
    $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->putJson('/api/settings/profile', [
            'name' => 'Updated Name',
            'phone' => '08123456789',
        ]);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => ['user' => ['name' => 'Updated Name', 'phone' => '08123456789']],
        ]);
});

test('authenticated user can update email', function () {
    $user = User::factory()->create(['status' => User::STATUS_ACTIVE, 'email' => 'old@example.com']);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->putJson('/api/settings/profile', [
            'email' => 'new@example.com',
        ]);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => ['user' => ['email' => 'new@example.com']],
        ]);
});

test('cannot update email to taken email', function () {
    User::factory()->create(['email' => 'taken@example.com']);
    $user = User::factory()->create(['status' => User::STATUS_ACTIVE, 'email' => 'user@example.com']);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->putJson('/api/settings/profile', [
            'email' => 'taken@example.com',
        ]);

    $response->assertStatus(422);
});

test('authenticated user can get account', function () {
    $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/settings/account');

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => ['timezone', 'language', 'member_since', 'email_verified'],
        ]);
});

test('authenticated user can update account', function () {
    $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->putJson('/api/settings/account', [
            'timezone' => 'Asia/Jakarta',
            'language' => 'en',
        ]);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => ['timezone' => 'Asia/Jakarta', 'language' => 'en'],
        ]);
});

test('authenticated user can change password', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'password' => bcrypt('current-password'),
    ]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->putJson('/api/settings/password', [
            'current_password' => 'current-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Password updated successfully.',
        ]);
});

test('cannot change password with wrong current password', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'password' => bcrypt('current-password'),
    ]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->putJson('/api/settings/password', [
            'current_password' => 'wrong-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

    $response->assertStatus(422);
});

test('authenticated user can get preferences', function () {
    $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/settings/preferences');

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => ['theme', 'language', 'timezone'],
        ]);
});

test('authenticated user can update preferences', function () {
    $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->putJson('/api/settings/preferences', [
            'theme' => 'dark',
            'language' => 'en',
        ]);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => ['theme' => 'dark', 'language' => 'en'],
        ]);
});

test('preferences validates theme value', function () {
    $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->putJson('/api/settings/preferences', [
            'theme' => 'invalid-theme',
        ]);

    $response->assertStatus(422);
});

test('authenticated user can get notification preferences', function () {
    $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/settings/notifications');

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => ['in_app', 'email', 'whatsapp'],
        ]);
});

test('authenticated user can update notification preferences', function () {
    $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->putJson('/api/settings/notifications', [
            'in_app' => true,
            'email' => false,
            'whatsapp' => true,
        ]);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => ['in_app' => true, 'email' => false, 'whatsapp' => true],
        ]);
});

test('authenticated user can list api keys', function () {
    $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $token = $user->createToken('auth-token')->plainTextToken;

    ApiKey::factory()->count(2)->create(['user_id' => $user->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/settings/api-keys');

    $response->assertOk()
        ->assertJson([
            'success' => true,
        ]);
});

test('authenticated user can create api key', function () {
    $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/settings/api-keys', [
            'name' => 'My API Key',
        ]);

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => ['id', 'name', 'plain_text_key'],
        ]);
});

test('authenticated user can delete api key', function () {
    $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $apiKey = ApiKey::factory()->create(['user_id' => $user->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->deleteJson('/api/settings/api-keys/' . $apiKey->uuid);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'API key deleted successfully.',
        ]);
});

test('cannot delete other users api key', function () {
    $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $otherUser = User::factory()->create();
    $apiKey = ApiKey::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->deleteJson('/api/settings/api-keys/' . $apiKey->uuid);

    $response->assertStatus(404);
});

test('cannot access other users api keys', function () {
    $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $otherUser = User::factory()->create();
    ApiKey::factory()->count(3)->create(['user_id' => $otherUser->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/settings/api-keys');

    $response->assertOk();
    expect(count($response->json('data')))->toBe(0);
});
