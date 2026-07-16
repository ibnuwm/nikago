<?php

declare(strict_types=1);

use App\Modules\Authentication\Models\User;
use App\Modules\Guest\Models\Guest;
use App\Modules\Seating\Models\SeatingAssignment;
use App\Modules\Seating\Models\SeatingTable;
use App\Modules\Wedding\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $this->token = $this->user->createToken('auth-token')->plainTextToken;
    $this->wedding = Wedding::factory()->create(['user_id' => $this->user->id]);
});

test('authenticated user can list seating tables', function () {
    SeatingTable::factory()->count(3)->create(['wedding_id' => $this->wedding->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson('/api/seatings');

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [],
            'meta' => ['current_page', 'last_page', 'per_page', 'total'],
        ]);
});

test('unauthenticated user cannot list seating tables', function () {
    $response = $this->getJson('/api/seatings');
    $response->assertUnauthorized();
});

test('authenticated user can create seating table', function () {
    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson('/api/seatings', [
            'wedding_id' => $this->wedding->id,
            'name' => 'Table 1',
            'capacity' => 8,
            'shape' => 'round',
        ]);

    $response->assertCreated()
        ->assertJson([
            'success' => true,
            'data' => ['name' => 'Table 1', 'capacity' => 8, 'shape' => 'round'],
        ]);
});

test('authenticated user can get seating table by uuid', function () {
    $table = SeatingTable::factory()->create(['wedding_id' => $this->wedding->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson('/api/seatings/' . $table->uuid);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => ['id' => $table->uuid],
        ]);
});

test('authenticated user can update seating table', function () {
    $table = SeatingTable::factory()->create(['wedding_id' => $this->wedding->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->putJson('/api/seatings/' . $table->uuid, [
            'name' => 'VIP Table',
            'capacity' => 4,
        ]);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => ['name' => 'VIP Table', 'capacity' => 4],
        ]);
});

test('authenticated user can delete seating table', function () {
    $table = SeatingTable::factory()->create(['wedding_id' => $this->wedding->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->deleteJson('/api/seatings/' . $table->uuid);

    $response->assertOk()
        ->assertJson(['success' => true]);

    $this->assertSoftDeleted($table);
});

test('authenticated user can assign guest to table', function () {
    $table = SeatingTable::factory()->create(['wedding_id' => $this->wedding->id, 'capacity' => 4]);
    $guest = Guest::factory()->create(['wedding_id' => $this->wedding->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson('/api/seatings/' . $table->uuid . '/assign', [
            'guest_id' => $guest->uuid,
            'seat_number' => 1,
        ]);

    $response->assertOk()
        ->assertJson(['success' => true]);
});

test('authenticated user can unassign guest from table', function () {
    $table = SeatingTable::factory()->create(['wedding_id' => $this->wedding->id]);
    $guest = Guest::factory()->create(['wedding_id' => $this->wedding->id]);
    $assignment = SeatingAssignment::factory()->create([
        'table_id' => $table->id,
        'guest_id' => $guest->id,
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->deleteJson('/api/seatings/' . $table->uuid . '/unassign/' . $assignment->uuid);

    $response->assertOk()
        ->assertJson(['success' => true]);

    $this->assertSoftDeleted($assignment);
});

test('authenticated user can auto-generate seating', function () {
    SeatingTable::factory()->count(2)->create(['wedding_id' => $this->wedding->id, 'capacity' => 4]);
    Guest::factory()->count(5)->create(['wedding_id' => $this->wedding->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson('/api/seatings/auto-generate', [
            'wedding_id' => $this->wedding->id,
        ]);

    $response->assertOk()
        ->assertJsonStructure(['success', 'message', 'data' => ['assigned']]);
});

test('authenticated user can preview seating', function () {
    SeatingTable::factory()->count(2)->create(['wedding_id' => $this->wedding->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson('/api/seatings/preview?wedding_id=' . $this->wedding->id);

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => ['tables', 'total_tables', 'total_guests', 'total_capacity'],
        ]);
});

test('authenticated user can export seating', function () {
    SeatingTable::factory()->count(2)->create(['wedding_id' => $this->wedding->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson('/api/seatings/export?wedding_id=' . $this->wedding->id);

    $response->assertOk()
        ->assertJsonStructure(['success', 'data']);
});

test('cannot assign guest to full table', function () {
    $table = SeatingTable::factory()->create(['wedding_id' => $this->wedding->id, 'capacity' => 1]);
    $guest1 = Guest::factory()->create(['wedding_id' => $this->wedding->id]);
    $guest2 = Guest::factory()->create(['wedding_id' => $this->wedding->id]);

    SeatingAssignment::factory()->create(['table_id' => $table->id, 'guest_id' => $guest1->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson('/api/seatings/' . $table->uuid . '/assign', [
            'guest_id' => $guest2->uuid,
        ]);

    $response->assertStatus(404);
});
