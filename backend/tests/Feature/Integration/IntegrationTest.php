<?php

declare(strict_types=1);

use App\Modules\Authentication\Models\User;
use App\Modules\Integration\Models\Webhook;
use Database\Seeders\IntegrationProviderSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(IntegrationProviderSeeder::class);
});

test('unauthenticated user cannot access integrations', function () {
    $response = $this->getJson('/api/integrations');
    $response->assertUnauthorized();

    $response = $this->getJson('/api/integrations/providers');
    $response->assertUnauthorized();

    $response = $this->postJson('/api/integrations/google/connect', []);
    $response->assertUnauthorized();

    $response = $this->postJson('/api/integrations/webhooks', []);
    $response->assertUnauthorized();
});

test('authenticated user can list integrations', function () {
    $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/integrations');

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                '*' => ['id', 'code', 'name', 'category', 'description', 'icon', 'is_connected'],
            ],
        ]);
});

test('authenticated user can list providers', function () {
    $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/integrations/providers');

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                '*' => ['id', 'code', 'name', 'category', 'description', 'icon', 'is_active'],
            ],
        ]);
});

test('authenticated user can connect google oauth', function () {
    $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/integrations/google/connect', [
            'access_token' => 'test-access-token',
            'refresh_token' => 'test-refresh-token',
        ]);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Google OAuth connected successfully.',
        ]);
});

test('authenticated user can disconnect google oauth', function () {
    $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/integrations/google/connect', ['access_token' => 'test']);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->deleteJson('/api/integrations/google/disconnect');

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Google OAuth disconnected successfully.',
        ]);
});

test('authenticated user can connect google calendar', function () {
    $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/integrations/calendar/connect', [
            'access_token' => 'test-access-token',
        ]);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Google Calendar connected successfully.',
        ]);
});

test('authenticated user can disconnect google calendar', function () {
    $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/integrations/calendar/connect', ['access_token' => 'test']);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->deleteJson('/api/integrations/calendar/disconnect');

    $response->assertOk();
});

test('authenticated user can connect whatsapp', function () {
    $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/integrations/whatsapp/connect', [
            'api_key' => 'test-api-key',
        ]);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'WhatsApp API connected successfully.',
        ]);
});

test('authenticated user can disconnect whatsapp', function () {
    $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/integrations/whatsapp/connect', ['api_key' => 'test']);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->deleteJson('/api/integrations/whatsapp/disconnect');

    $response->assertOk();
});

test('authenticated user can create webhook', function () {
    $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/integrations/webhooks', [
            'name' => 'Test Webhook',
            'url' => 'https://example.com/webhook',
            'events' => ['booking.created', 'payment.completed'],
        ]);

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => ['id', 'uuid', 'name', 'url', 'events', 'is_active'],
        ]);
});

test('authenticated user can list webhooks', function () {
    $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $token = $user->createToken('auth-token')->plainTextToken;

    Webhook::factory()->count(3)->create(['user_id' => $user->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/integrations/webhooks');

    $response->assertOk()
        ->assertJson(['success' => true]);
});

test('authenticated user can delete webhook', function () {
    $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $webhook = Webhook::factory()->create(['user_id' => $user->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->deleteJson('/api/integrations/webhooks/' . $webhook->uuid);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Webhook deleted successfully.',
        ]);
});

test('cannot delete other users webhook', function () {
    $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $otherUser = User::factory()->create();
    $webhook = Webhook::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->deleteJson('/api/integrations/webhooks/' . $webhook->uuid);

    $response->assertStatus(404);
});

test('authenticated user can test integration connection', function () {
    $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/integrations/test', [
            'provider' => 'GOOGLE_OAUTH',
        ]);

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => ['provider', 'is_connected', 'status'],
        ]);
});
