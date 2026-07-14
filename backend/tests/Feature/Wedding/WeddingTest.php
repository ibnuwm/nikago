<?php

declare(strict_types=1);

use App\Modules\Authentication\Models\User;
use App\Modules\Wedding\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authenticated user can get their weddings', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    Wedding::factory()->count(3)->create([
        'user_id' => $user->id,
        'tenant_id' => 1,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/weddings');

    $response->assertOk()
        ->assertJson([
            'success' => true,
        ])
        ->assertJsonCount(3, 'data');
});

test('unauthenticated user cannot get weddings', function () {
    $response = $this->getJson('/api/weddings');

    $response->assertUnauthorized();
});

test('user can only see their own weddings', function () {
    $user1 = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $user2 = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    Wedding::factory()->count(2)->create([
        'user_id' => $user1->id,
        'tenant_id' => 1,
    ]);

    Wedding::factory()->count(3)->create([
        'user_id' => $user2->id,
        'tenant_id' => 1,
    ]);

    $token = $user1->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/weddings');

    $response->assertOk()
        ->assertJsonCount(2, 'data');
});

test('authenticated user can create wedding', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/weddings', [
            'title' => 'My Wedding',
            'theme' => 'Romantic',
        ]);

    $response->assertCreated()
        ->assertJson([
            'success' => true,
            'data' => [
                'title' => 'My Wedding',
                'theme' => 'Romantic',
                'status' => 'draft',
            ],
        ]);

    $this->assertDatabaseHas('weddings', [
        'user_id' => $user->id,
        'title' => 'My Wedding',
    ]);
});

test('authenticated user can get wedding by uuid', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $wedding = Wedding::factory()->create([
        'user_id' => $user->id,
        'tenant_id' => 1,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson("/api/weddings/{$wedding->uuid}");

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => [
                'id' => $wedding->uuid,
                'title' => $wedding->title,
            ],
        ]);
});

test('user cannot access other users wedding', function () {
    $user1 = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $user2 = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $wedding = Wedding::factory()->create([
        'user_id' => $user2->id,
        'tenant_id' => 1,
    ]);

    $token = $user1->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson("/api/weddings/{$wedding->uuid}");

    $response->assertNotFound();
});

test('authenticated user can update wedding', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $wedding = Wedding::factory()->create([
        'user_id' => $user->id,
        'tenant_id' => 1,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->putJson("/api/weddings/{$wedding->uuid}", [
            'title' => 'Updated Wedding',
        ]);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => [
                'title' => 'Updated Wedding',
            ],
        ]);

    $this->assertDatabaseHas('weddings', [
        'id' => $wedding->id,
        'title' => 'Updated Wedding',
    ]);
});

test('authenticated user can delete wedding', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $wedding = Wedding::factory()->create([
        'user_id' => $user->id,
        'tenant_id' => 1,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->deleteJson("/api/weddings/{$wedding->uuid}");

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Wedding deleted successfully.',
        ]);

    $this->assertSoftDeleted('weddings', [
        'id' => $wedding->id,
    ]);
});

test('authenticated user can publish wedding', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $wedding = Wedding::factory()->create([
        'user_id' => $user->id,
        'tenant_id' => 1,
        'status' => Wedding::STATUS_DRAFT,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->patchJson("/api/weddings/{$wedding->uuid}/publish");

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => [
                'status' => Wedding::STATUS_PUBLISHED,
            ],
        ]);

    $this->assertDatabaseHas('weddings', [
        'id' => $wedding->id,
        'status' => Wedding::STATUS_PUBLISHED,
    ]);
});

test('authenticated user can archive wedding', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $wedding = Wedding::factory()->create([
        'user_id' => $user->id,
        'tenant_id' => 1,
        'status' => Wedding::STATUS_PUBLISHED,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->patchJson("/api/weddings/{$wedding->uuid}/archive");

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => [
                'status' => Wedding::STATUS_ARCHIVED,
            ],
        ]);

    $this->assertDatabaseHas('weddings', [
        'id' => $wedding->id,
        'status' => Wedding::STATUS_ARCHIVED,
    ]);
});

test('wedding requires title', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/weddings', []);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['title']);
});

test('weddings can be filtered by status', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    Wedding::factory()->count(2)->create([
        'user_id' => $user->id,
        'tenant_id' => 1,
        'status' => Wedding::STATUS_DRAFT,
    ]);

    Wedding::factory()->count(3)->create([
        'user_id' => $user->id,
        'tenant_id' => 1,
        'status' => Wedding::STATUS_PUBLISHED,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/weddings?status=published');

    $response->assertOk()
        ->assertJsonCount(3, 'data');
});

test('weddings can be searched', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    Wedding::factory()->create([
        'user_id' => $user->id,
        'tenant_id' => 1,
        'title' => 'Summer Wedding',
    ]);

    Wedding::factory()->create([
        'user_id' => $user->id,
        'tenant_id' => 1,
        'title' => 'Winter Wedding',
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/weddings?search=Summer');

    $response->assertOk()
        ->assertJsonCount(1, 'data');
});

test('inactive user cannot get weddings', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_INACTIVE,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/weddings');

    $response->assertForbidden();
});

test('inactive user cannot create wedding', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_INACTIVE,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/weddings', [
            'title' => 'Test Wedding',
        ]);

    $response->assertForbidden();
});

test('suspended user cannot get weddings', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_SUSPENDED,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/weddings');

    $response->assertForbidden();
});

test('invalid uuid format returns 404', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/weddings/invalid-uuid');

    $response->assertNotFound();
});

test('unauthorized user cannot update wedding', function () {
    $user1 = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $user2 = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $wedding = Wedding::factory()->create([
        'user_id' => $user2->id,
        'tenant_id' => 1,
    ]);

    $token = $user1->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->putJson("/api/weddings/{$wedding->uuid}", [
            'title' => 'Hacked Wedding',
        ]);

    $response->assertNotFound();
});

test('unauthorized user cannot delete wedding', function () {
    $user1 = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $user2 = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $wedding = Wedding::factory()->create([
        'user_id' => $user2->id,
        'tenant_id' => 1,
    ]);

    $token = $user1->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->deleteJson("/api/weddings/{$wedding->uuid}");

    $response->assertNotFound();
});
