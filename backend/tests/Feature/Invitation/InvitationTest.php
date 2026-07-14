<?php

declare(strict_types=1);

use App\Modules\Authentication\Models\User;
use App\Modules\Invitation\Models\Invitation;
use App\Modules\Wedding\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authenticated user can get their invitations', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $wedding = Wedding::factory()->create([
        'user_id' => $user->id,
        'tenant_id' => 1,
    ]);

    Invitation::factory()->count(3)->create([
        'wedding_id' => $wedding->id,
        'tenant_id' => 1,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/invitations');

    $response->assertOk()
        ->assertJson([
            'success' => true,
        ])
        ->assertJsonCount(3, 'data');
});

test('unauthenticated user cannot get invitations', function () {
    $response = $this->getJson('/api/invitations');

    $response->assertUnauthorized();
});

test('user can only see their own invitations', function () {
    $user1 = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $user2 = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $wedding1 = Wedding::factory()->create([
        'user_id' => $user1->id,
        'tenant_id' => 1,
    ]);

    $wedding2 = Wedding::factory()->create([
        'user_id' => $user2->id,
        'tenant_id' => 1,
    ]);

    Invitation::factory()->count(2)->create([
        'wedding_id' => $wedding1->id,
        'tenant_id' => 1,
    ]);

    Invitation::factory()->count(3)->create([
        'wedding_id' => $wedding2->id,
        'tenant_id' => 1,
    ]);

    $token = $user1->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/invitations');

    $response->assertOk()
        ->assertJsonCount(2, 'data');
});

test('authenticated user can create invitation', function () {
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
        ->postJson('/api/invitations', [
            'wedding_id' => $wedding->uuid,
            'title' => 'My Invitation',
        ]);

    $response->assertCreated()
        ->assertJson([
            'success' => true,
            'data' => [
                'title' => 'My Invitation',
                'status' => 'draft',
            ],
        ]);

    $this->assertDatabaseHas('invitations', [
        'wedding_id' => $wedding->id,
        'title' => 'My Invitation',
    ]);
});

test('authenticated user can get invitation by uuid', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $wedding = Wedding::factory()->create([
        'user_id' => $user->id,
        'tenant_id' => 1,
    ]);

    $invitation = Invitation::factory()->create([
        'wedding_id' => $wedding->id,
        'tenant_id' => 1,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson("/api/invitations/{$invitation->uuid}");

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => [
                'id' => $invitation->uuid,
                'title' => $invitation->title,
            ],
        ]);
});

test('user cannot access other users invitation', function () {
    $user1 = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $user2 = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $wedding2 = Wedding::factory()->create([
        'user_id' => $user2->id,
        'tenant_id' => 1,
    ]);

    $invitation = Invitation::factory()->create([
        'wedding_id' => $wedding2->id,
        'tenant_id' => 1,
    ]);

    $token = $user1->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson("/api/invitations/{$invitation->uuid}");

    $response->assertNotFound();
});

test('authenticated user can update invitation', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $wedding = Wedding::factory()->create([
        'user_id' => $user->id,
        'tenant_id' => 1,
    ]);

    $invitation = Invitation::factory()->create([
        'wedding_id' => $wedding->id,
        'tenant_id' => 1,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->putJson("/api/invitations/{$invitation->uuid}", [
            'title' => 'Updated Invitation',
        ]);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => [
                'title' => 'Updated Invitation',
            ],
        ]);

    $this->assertDatabaseHas('invitations', [
        'id' => $invitation->id,
        'title' => 'Updated Invitation',
    ]);
});

test('authenticated user can delete invitation', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $wedding = Wedding::factory()->create([
        'user_id' => $user->id,
        'tenant_id' => 1,
    ]);

    $invitation = Invitation::factory()->create([
        'wedding_id' => $wedding->id,
        'tenant_id' => 1,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->deleteJson("/api/invitations/{$invitation->uuid}");

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Invitation deleted successfully.',
        ]);

    $this->assertSoftDeleted('invitations', [
        'id' => $invitation->id,
    ]);
});

test('authenticated user can publish invitation', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $wedding = Wedding::factory()->create([
        'user_id' => $user->id,
        'tenant_id' => 1,
    ]);

    $invitation = Invitation::factory()->create([
        'wedding_id' => $wedding->id,
        'tenant_id' => 1,
        'status' => Invitation::STATUS_DRAFT,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->patchJson("/api/invitations/{$invitation->uuid}/publish");

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => [
                'status' => Invitation::STATUS_PUBLISHED,
            ],
        ]);

    $this->assertDatabaseHas('invitations', [
        'id' => $invitation->id,
        'status' => Invitation::STATUS_PUBLISHED,
    ]);
});

test('authenticated user can unpublish invitation to draft', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $wedding = Wedding::factory()->create([
        'user_id' => $user->id,
        'tenant_id' => 1,
    ]);

    $invitation = Invitation::factory()->published()->create([
        'wedding_id' => $wedding->id,
        'tenant_id' => 1,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->patchJson("/api/invitations/{$invitation->uuid}/draft");

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => [
                'status' => Invitation::STATUS_DRAFT,
            ],
        ]);

    $this->assertDatabaseHas('invitations', [
        'id' => $invitation->id,
        'status' => Invitation::STATUS_DRAFT,
    ]);
});

test('authenticated user can duplicate invitation', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $wedding = Wedding::factory()->create([
        'user_id' => $user->id,
        'tenant_id' => 1,
    ]);

    $invitation = Invitation::factory()->create([
        'wedding_id' => $wedding->id,
        'tenant_id' => 1,
        'title' => 'Original Invitation',
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson("/api/invitations/{$invitation->uuid}/duplicate");

    $response->assertCreated()
        ->assertJson([
            'success' => true,
            'data' => [
                'title' => 'Original Invitation (Copy)',
            ],
        ]);

    $this->assertDatabaseHas('invitations', [
        'wedding_id' => $wedding->id,
        'title' => 'Original Invitation (Copy)',
    ]);
});

test('authenticated user can preview invitation', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $wedding = Wedding::factory()->create([
        'user_id' => $user->id,
        'tenant_id' => 1,
    ]);

    $invitation = Invitation::factory()->create([
        'wedding_id' => $wedding->id,
        'tenant_id' => 1,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson("/api/invitations/{$invitation->uuid}/preview");

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => [
                'id' => $invitation->uuid,
            ],
        ]);
});

test('invitation requires title and wedding_id', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/invitations', []);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['title', 'wedding_id']);
});

test('invitations can be filtered by status', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $wedding = Wedding::factory()->create([
        'user_id' => $user->id,
        'tenant_id' => 1,
    ]);

    Invitation::factory()->count(2)->create([
        'wedding_id' => $wedding->id,
        'tenant_id' => 1,
        'status' => Invitation::STATUS_DRAFT,
    ]);

    Invitation::factory()->count(3)->published()->create([
        'wedding_id' => $wedding->id,
        'tenant_id' => 1,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/invitations?status=published');

    $response->assertOk()
        ->assertJsonCount(3, 'data');
});

test('invitations can be searched', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $wedding = Wedding::factory()->create([
        'user_id' => $user->id,
        'tenant_id' => 1,
    ]);

    Invitation::factory()->create([
        'wedding_id' => $wedding->id,
        'tenant_id' => 1,
        'title' => 'Summer Invitation',
    ]);

    Invitation::factory()->create([
        'wedding_id' => $wedding->id,
        'tenant_id' => 1,
        'title' => 'Winter Invitation',
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/invitations?search=Summer');

    $response->assertOk()
        ->assertJsonCount(1, 'data');
});

test('inactive user cannot get invitations', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_INACTIVE,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/invitations');

    $response->assertForbidden();
});

test('inactive user cannot create invitation', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_INACTIVE,
        'tenant_id' => 1,
    ]);

    $wedding = Wedding::factory()->create([
        'user_id' => $user->id,
        'tenant_id' => 1,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/invitations', [
            'wedding_id' => $wedding->uuid,
            'title' => 'Test Invitation',
        ]);

    $response->assertForbidden();
});

test('suspended user cannot get invitations', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_SUSPENDED,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/invitations');

    $response->assertForbidden();
});

test('invalid uuid format returns 404', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/invitations/invalid-uuid');

    $response->assertNotFound();
});

test('unauthorized user cannot update invitation', function () {
    $user1 = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $user2 = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $wedding2 = Wedding::factory()->create([
        'user_id' => $user2->id,
        'tenant_id' => 1,
    ]);

    $invitation = Invitation::factory()->create([
        'wedding_id' => $wedding2->id,
        'tenant_id' => 1,
    ]);

    $token = $user1->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->putJson("/api/invitations/{$invitation->uuid}", [
            'title' => 'Hacked Invitation',
        ]);

    $response->assertNotFound();
});

test('unauthorized user cannot delete invitation', function () {
    $user1 = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $user2 = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $wedding2 = Wedding::factory()->create([
        'user_id' => $user2->id,
        'tenant_id' => 1,
    ]);

    $invitation = Invitation::factory()->create([
        'wedding_id' => $wedding2->id,
        'tenant_id' => 1,
    ]);

    $token = $user1->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->deleteJson("/api/invitations/{$invitation->uuid}");

    $response->assertNotFound();
});

test('unauthorized user cannot publish invitation', function () {
    $user1 = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $user2 = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $wedding2 = Wedding::factory()->create([
        'user_id' => $user2->id,
        'tenant_id' => 1,
    ]);

    $invitation = Invitation::factory()->create([
        'wedding_id' => $wedding2->id,
        'tenant_id' => 1,
    ]);

    $token = $user1->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->patchJson("/api/invitations/{$invitation->uuid}/publish");

    $response->assertNotFound();
});

test('unauthorized user cannot draft invitation', function () {
    $user1 = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $user2 = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $wedding2 = Wedding::factory()->create([
        'user_id' => $user2->id,
        'tenant_id' => 1,
    ]);

    $invitation = Invitation::factory()->published()->create([
        'wedding_id' => $wedding2->id,
        'tenant_id' => 1,
    ]);

    $token = $user1->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->patchJson("/api/invitations/{$invitation->uuid}/draft");

    $response->assertNotFound();
});

test('unauthorized user cannot duplicate invitation', function () {
    $user1 = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $user2 = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $wedding2 = Wedding::factory()->create([
        'user_id' => $user2->id,
        'tenant_id' => 1,
    ]);

    $invitation = Invitation::factory()->create([
        'wedding_id' => $wedding2->id,
        'tenant_id' => 1,
    ]);

    $token = $user1->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson("/api/invitations/{$invitation->uuid}/duplicate");

    $response->assertNotFound();
});
