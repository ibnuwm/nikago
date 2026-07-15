<?php

declare(strict_types=1);

use App\Modules\Authentication\Models\User;
use App\Modules\Guest\Models\Guest;
use App\Modules\Wedding\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

uses(RefreshDatabase::class);

test('authenticated user can get guests list', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $wedding = Wedding::factory()->create([
        'user_id' => $user->id,
        'tenant_id' => 1,
    ]);

    Guest::factory()->count(3)->create([
        'wedding_id' => $wedding->id,
        'tenant_id' => 1,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/guests');

    $response->assertOk()
        ->assertJson([
            'success' => true,
        ])
        ->assertJsonCount(3, 'data');
});

test('unauthenticated user cannot get guests', function () {
    $response = $this->getJson('/api/guests');

    $response->assertUnauthorized();
});

test('user can only see guests from their own weddings', function () {
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

    Guest::factory()->count(2)->create([
        'wedding_id' => $wedding1->id,
        'tenant_id' => 1,
    ]);

    Guest::factory()->count(3)->create([
        'wedding_id' => $wedding2->id,
        'tenant_id' => 1,
    ]);

    $token = $user1->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/guests');

    $response->assertOk()
        ->assertJsonCount(2, 'data');
});

test('authenticated user can create guest', function () {
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
        ->postJson('/api/guests', [
            'wedding_id' => $wedding->id,
            'name' => 'John Doe',
            'phone' => '08123456789',
            'pax' => 3,
        ]);

    $response->assertCreated()
        ->assertJson([
            'success' => true,
            'data' => [
                'name' => 'John Doe',
                'phone' => '08123456789',
                'pax' => 3,
                'status' => 'active',
            ],
        ]);

    $this->assertDatabaseHas('guests', [
        'name' => 'John Doe',
    ]);
});

test('authenticated user can get guest by uuid', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $wedding = Wedding::factory()->create([
        'user_id' => $user->id,
        'tenant_id' => 1,
    ]);

    $guest = Guest::factory()->create([
        'wedding_id' => $wedding->id,
        'tenant_id' => 1,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson("/api/guests/{$guest->uuid}");

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => [
                'id' => $guest->uuid,
                'name' => $guest->name,
            ],
        ]);
});

test('user cannot access other users guest', function () {
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

    $guest = Guest::factory()->create([
        'wedding_id' => $wedding->id,
        'tenant_id' => 1,
    ]);

    $token = $user1->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson("/api/guests/{$guest->uuid}");

    $response->assertNotFound();
});

test('authenticated user can update guest', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $wedding = Wedding::factory()->create([
        'user_id' => $user->id,
        'tenant_id' => 1,
    ]);

    $guest = Guest::factory()->create([
        'wedding_id' => $wedding->id,
        'tenant_id' => 1,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->putJson("/api/guests/{$guest->uuid}", [
            'name' => 'Updated Name',
        ]);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => [
                'name' => 'Updated Name',
            ],
        ]);

    $this->assertDatabaseHas('guests', [
        'id' => $guest->id,
        'name' => 'Updated Name',
    ]);
});

test('authenticated user can delete guest', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $wedding = Wedding::factory()->create([
        'user_id' => $user->id,
        'tenant_id' => 1,
    ]);

    $guest = Guest::factory()->create([
        'wedding_id' => $wedding->id,
        'tenant_id' => 1,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->deleteJson("/api/guests/{$guest->uuid}");

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Guest deleted successfully.',
        ]);

    $this->assertSoftDeleted('guests', [
        'id' => $guest->id,
    ]);
});

test('guest requires name', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/guests', [
            'wedding_id' => 1,
        ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['name']);
});

test('guests can be searched', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $wedding = Wedding::factory()->create([
        'user_id' => $user->id,
        'tenant_id' => 1,
    ]);

    Guest::factory()->create([
        'wedding_id' => $wedding->id,
        'tenant_id' => 1,
        'name' => 'John Doe',
    ]);

    Guest::factory()->create([
        'wedding_id' => $wedding->id,
        'tenant_id' => 1,
        'name' => 'Jane Smith',
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/guests?search=John');

    $response->assertOk()
        ->assertJsonCount(1, 'data');
});

test('guests can be filtered by status', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $wedding = Wedding::factory()->create([
        'user_id' => $user->id,
        'tenant_id' => 1,
    ]);

    Guest::factory()->count(2)->create([
        'wedding_id' => $wedding->id,
        'tenant_id' => 1,
        'status' => Guest::STATUS_ACTIVE,
    ]);

    Guest::factory()->count(1)->create([
        'wedding_id' => $wedding->id,
        'tenant_id' => 1,
        'status' => Guest::STATUS_INACTIVE,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/guests?status=inactive');

    $response->assertOk()
        ->assertJsonCount(1, 'data');
});

test('inactive user cannot get guests', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_INACTIVE,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/guests');

    $response->assertForbidden();
});

test('inactive user cannot create guest', function () {
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
        ->postJson('/api/guests', [
            'wedding_id' => $wedding->id,
            'name' => 'Test',
        ]);

    $response->assertForbidden();
});

test('authenticated user can export guests', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $wedding = Wedding::factory()->create([
        'user_id' => $user->id,
        'tenant_id' => 1,
    ]);

    Guest::factory()->count(2)->create([
        'wedding_id' => $wedding->id,
        'tenant_id' => 1,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/guests/export');

    $response->assertOk()
        ->assertJson([
            'success' => true,
        ])
        ->assertJsonCount(3, 'data');
});

test('authenticated user can import guests via csv', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $wedding = Wedding::factory()->create([
        'user_id' => $user->id,
        'tenant_id' => 1,
    ]);

    $csv = "name,phone,email\nJohn Doe,08123456789,john@example.com\nJane Smith,08198765432,jane@example.com";

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->post('/api/guests/import', [
            'wedding_id' => $wedding->id,
            'file' => UploadedFile::fake()->createWithContent('guests.csv', $csv),
        ]);

    $response->assertOk()
        ->assertJson([
            'success' => true,
        ]);

    $this->assertDatabaseHas('guests', ['name' => 'John Doe']);
    $this->assertDatabaseHas('guests', ['name' => 'Jane Smith']);
});

test('import requires file', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->post('/api/guests/import', [
            'wedding_id' => 1,
        ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['file']);
});

test('authenticated user can check in guest by qr code', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $wedding = Wedding::factory()->create([
        'user_id' => $user->id,
        'tenant_id' => 1,
    ]);

    $guest = Guest::factory()->create([
        'wedding_id' => $wedding->id,
        'tenant_id' => 1,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->patchJson("/api/guests/{$guest->uuid}/check-in", [
            'qr_code' => $guest->qr_code,
        ]);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => [
                'name' => $guest->name,
            ],
        ]);
});

test('check in with invalid qr code returns 404', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $wedding = Wedding::factory()->create([
        'user_id' => $user->id,
        'tenant_id' => 1,
    ]);

    $guest = Guest::factory()->create([
        'wedding_id' => $wedding->id,
        'tenant_id' => 1,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->patchJson("/api/guests/{$guest->uuid}/check-in", [
            'qr_code' => 'wrong-qr-code',
        ]);

    $response->assertNotFound();
});

test('authenticated user can send invitation to guest', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $wedding = Wedding::factory()->create([
        'user_id' => $user->id,
        'tenant_id' => 1,
    ]);

    $guest = Guest::factory()->create([
        'wedding_id' => $wedding->id,
        'tenant_id' => 1,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/guests/send-invitation', [
            'guest_uuid' => $guest->uuid,
        ]);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Invitation sent successfully.',
        ]);

    $this->assertNotNull($guest->fresh()->invitation_sent_at);
});

test('check in history returns checked in guests', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $wedding = Wedding::factory()->create([
        'user_id' => $user->id,
        'tenant_id' => 1,
    ]);

    Guest::factory()->count(2)->create([
        'wedding_id' => $wedding->id,
        'tenant_id' => 1,
        'invitation_sent_at' => now(),
    ]);

    Guest::factory()->create([
        'wedding_id' => $wedding->id,
        'tenant_id' => 1,
        'invitation_sent_at' => null,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/guests/check-in-history');

    $response->assertOk()
        ->assertJsonCount(2, 'data');
});
