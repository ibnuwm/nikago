<?php

declare(strict_types=1);

use App\Modules\Authentication\Models\User;
use App\Modules\Guest\Models\Guest;
use App\Modules\RSVP\Models\Rsvp;
use App\Modules\RSVP\Models\RsvpLog;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authenticated user can get rsvps', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $guest = Guest::factory()->create([
        'tenant_id' => $user->tenant_id,
        'wedding_id' => 1,
    ]);

    Rsvp::factory()->create([
        'guest_id' => $guest->id,
        'tenant_id' => $user->tenant_id,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/rsvps');

    $response->assertOk()
        ->assertJson([
            'success' => true,
        ])
        ->assertJsonCount(1, 'data');
});

test('unauthenticated user cannot get rsvps', function () {
    $response = $this->getJson('/api/rsvps');

    $response->assertUnauthorized();
});

test('authenticated user can create rsvp', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $guest = Guest::factory()->create([
        'tenant_id' => $user->tenant_id,
        'wedding_id' => 1,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/rsvps', [
            'guest_uuid' => $guest->uuid,
            'attendance' => 'YES',
            'total_guest' => 2,
            'message' => 'Happy wedding!',
        ]);

    $response->assertCreated()
        ->assertJson([
            'success' => true,
            'data' => [
                'attendance' => 'YES',
                'total_guest' => 2,
                'message' => 'Happy wedding!',
            ],
        ]);

    $this->assertDatabaseHas('rsvps', [
        'guest_id' => $guest->id,
        'attendance' => 'YES',
    ]);

    $this->assertDatabaseHas('rsvp_logs', [
        'new_status' => 'YES',
    ]);
});

test('authenticated user can get rsvp by uuid', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $guest = Guest::factory()->create([
        'tenant_id' => $user->tenant_id,
        'wedding_id' => 1,
    ]);

    $rsvp = Rsvp::factory()->create([
        'guest_id' => $guest->id,
        'tenant_id' => $user->tenant_id,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson("/api/rsvps/{$rsvp->uuid}");

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => [
                'id' => $rsvp->uuid,
                'attendance' => $rsvp->attendance,
            ],
        ]);
});

test('authenticated user can update rsvp', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $guest = Guest::factory()->create([
        'tenant_id' => $user->tenant_id,
        'wedding_id' => 1,
    ]);

    $rsvp = Rsvp::factory()->yes()->create([
        'guest_id' => $guest->id,
        'tenant_id' => $user->tenant_id,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->putJson("/api/rsvps/{$rsvp->uuid}", [
            'attendance' => 'NO',
        ]);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => [
                'attendance' => 'NO',
            ],
        ]);

    $this->assertDatabaseHas('rsvp_logs', [
        'rsvp_id' => $rsvp->id,
        'old_status' => 'YES',
        'new_status' => 'NO',
    ]);
});

test('authenticated user can delete rsvp', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $guest = Guest::factory()->create([
        'tenant_id' => $user->tenant_id,
        'wedding_id' => 1,
    ]);

    $rsvp = Rsvp::factory()->create([
        'guest_id' => $guest->id,
        'tenant_id' => $user->tenant_id,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->deleteJson("/api/rsvps/{$rsvp->uuid}");

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'RSVP deleted successfully.',
        ]);

    $this->assertDatabaseMissing('rsvps', [
        'id' => $rsvp->id,
    ]);
});

test('authenticated user can get statistics', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $guest1 = Guest::factory()->create(['tenant_id' => $user->tenant_id, 'wedding_id' => 1]);
    $guest2 = Guest::factory()->create(['tenant_id' => $user->tenant_id, 'wedding_id' => 1]);
    $guest3 = Guest::factory()->create(['tenant_id' => $user->tenant_id, 'wedding_id' => 1]);

    Rsvp::factory()->yes()->create(['guest_id' => $guest1->id, 'tenant_id' => $user->tenant_id, 'total_guest' => 2]);
    Rsvp::factory()->no()->create(['guest_id' => $guest2->id, 'tenant_id' => $user->tenant_id, 'total_guest' => 1]);
    Rsvp::factory()->maybe()->create(['guest_id' => $guest3->id, 'tenant_id' => $user->tenant_id, 'total_guest' => 3]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/rsvps/statistics');

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => [
                'total_rsvps' => 3,
                'yes' => 1,
                'no' => 1,
                'maybe' => 1,
                'total_guests' => 6,
                'attendance_rate' => 33.3,
            ],
        ]);
});

test('authenticated user can export rsvps', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $guest = Guest::factory()->create(['tenant_id' => $user->tenant_id, 'wedding_id' => 1]);

    Rsvp::factory()->create([
        'guest_id' => $guest->id,
        'tenant_id' => $user->tenant_id,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/rsvps/export');

    $response->assertOk()
        ->assertHeader('Content-Type', 'text/csv; charset=utf-8');
});

test('rsvp not found returns 404', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/rsvps/00000000-0000-0000-0000-000000000000');

    $response->assertNotFound();
});

test('inactive user cannot get rsvps', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_INACTIVE,
        'tenant_id' => 1,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/rsvps');

    $response->assertForbidden();
});

test('suspended user cannot get rsvps', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_SUSPENDED,
        'tenant_id' => 1,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/rsvps');

    $response->assertForbidden();
});

test('rsvp requires guest_uuid and attendance', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/rsvps', []);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['guest_uuid', 'attendance']);
});

test('rsvp attendance must be valid', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $guest = Guest::factory()->create([
        'tenant_id' => $user->tenant_id,
        'wedding_id' => 1,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/rsvps', [
            'guest_uuid' => $guest->uuid,
            'attendance' => 'INVALID',
        ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['attendance']);
});

test('rsvp total_guest must be at least 1', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $guest = Guest::factory()->create([
        'tenant_id' => $user->tenant_id,
        'wedding_id' => 1,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/rsvps', [
            'guest_uuid' => $guest->uuid,
            'attendance' => 'YES',
            'total_guest' => 0,
        ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['total_guest']);
});

test('rsvp creates log on create', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $guest = Guest::factory()->create([
        'tenant_id' => $user->tenant_id,
        'wedding_id' => 1,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/rsvps', [
            'guest_uuid' => $guest->uuid,
            'attendance' => 'YES',
            'total_guest' => 1,
        ]);

    $rsvp = Rsvp::where('guest_id', $guest->id)->first();

    $this->assertDatabaseHas('rsvp_logs', [
        'rsvp_id' => $rsvp->id,
        'old_status' => null,
        'new_status' => 'YES',
    ]);
});

test('rsvp creates log on update', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $guest = Guest::factory()->create([
        'tenant_id' => $user->tenant_id,
        'wedding_id' => 1,
    ]);

    $rsvp = Rsvp::factory()->yes()->create([
        'guest_id' => $guest->id,
        'tenant_id' => $user->tenant_id,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $this->withHeader('Authorization', 'Bearer ' . $token)
        ->putJson("/api/rsvps/{$rsvp->uuid}", [
            'attendance' => 'NO',
        ]);

    $this->assertDatabaseHas('rsvp_logs', [
        'rsvp_id' => $rsvp->id,
        'old_status' => 'YES',
        'new_status' => 'NO',
    ]);
});

test('rsvp update is idempotent for same attendance', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $guest = Guest::factory()->create([
        'tenant_id' => $user->tenant_id,
        'wedding_id' => 1,
    ]);

    $rsvp = Rsvp::factory()->yes()->create([
        'guest_id' => $guest->id,
        'tenant_id' => $user->tenant_id,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $this->withHeader('Authorization', 'Bearer ' . $token)
        ->putJson("/api/rsvps/{$rsvp->uuid}", [
            'attendance' => 'YES',
        ]);

    $logs = RsvpLog::where('rsvp_id', $rsvp->id)->count();

    $this->assertEquals(0, $logs);
});
