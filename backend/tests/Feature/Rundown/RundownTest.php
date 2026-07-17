<?php

declare(strict_types=1);

use App\Modules\Authentication\Models\User;
use App\Modules\Rundown\Models\Rundown;
use App\Modules\Rundown\Models\RundownItem;
use App\Modules\Wedding\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $this->token = $this->user->createToken('auth-token')->plainTextToken;
    $this->wedding = Wedding::factory()->create(['user_id' => $this->user->id]);
});

test('authenticated user can list rundowns', function () {
    Rundown::factory()->count(3)->create(['wedding_id' => $this->wedding->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson('/api/rundowns');

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [],
            'meta' => ['current_page', 'last_page', 'per_page', 'total'],
        ]);
});

test('unauthenticated user cannot list rundowns', function () {
    $response = $this->getJson('/api/rundowns');
    $response->assertUnauthorized();
});

test('authenticated user can create rundown', function () {
    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson('/api/rundowns', [
            'wedding_id' => $this->wedding->id,
            'title' => 'Test Rundown',
            'description' => 'Test description',
        ]);

    $response->assertCreated()
        ->assertJson([
            'success' => true,
            'data' => ['title' => 'Test Rundown', 'description' => 'Test description'],
        ]);
});

test('authenticated user can create rundown with items', function () {
    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson('/api/rundowns', [
            'wedding_id' => $this->wedding->id,
            'title' => 'Rundown with Items',
            'items' => [
                ['title' => 'Item 1', 'start_time' => '08:00', 'end_time' => '09:00', 'pic' => 'PIC 1', 'sort_order' => 0],
                ['title' => 'Item 2', 'start_time' => '09:00', 'end_time' => '10:00', 'pic' => 'PIC 2', 'sort_order' => 1],
            ],
        ]);

    $response->assertCreated()
        ->assertJson([
            'success' => true,
            'data' => ['title' => 'Rundown with Items'],
        ]);

    expect(RundownItem::count())->toBe(2);
});

test('authenticated user can get rundown by uuid', function () {
    $rundown = Rundown::factory()->create(['wedding_id' => $this->wedding->id]);
    RundownItem::factory()->count(3)->create(['rundown_id' => $rundown->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson('/api/rundowns/' . $rundown->uuid);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => ['id' => $rundown->uuid],
        ]);
});

test('authenticated user can update rundown', function () {
    $rundown = Rundown::factory()->create(['wedding_id' => $this->wedding->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->putJson('/api/rundowns/' . $rundown->uuid, [
            'title' => 'Updated Title',
        ]);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => ['title' => 'Updated Title'],
        ]);
});

test('authenticated user can delete rundown', function () {
    $rundown = Rundown::factory()->create(['wedding_id' => $this->wedding->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->deleteJson('/api/rundowns/' . $rundown->uuid);

    $response->assertOk()
        ->assertJson(['success' => true]);

    $this->assertSoftDeleted($rundown);
});

test('authenticated user can publish rundown', function () {
    $rundown = Rundown::factory()->create(['wedding_id' => $this->wedding->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->patchJson('/api/rundowns/' . $rundown->uuid . '/publish');

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => ['status' => 'published'],
        ]);

    expect($rundown->fresh()->published_at)->not->toBeNull();
});

test('publishing already published rundown is idempotent', function () {
    $rundown = Rundown::factory()->create(['wedding_id' => $this->wedding->id]);

    $firstResponse = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->patchJson('/api/rundowns/' . $rundown->uuid . '/publish');

    $firstResponse->assertOk();
    $firstPublishedAt = $rundown->fresh()->published_at;

    $secondResponse = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->patchJson('/api/rundowns/' . $rundown->uuid . '/publish');

    $secondResponse->assertOk()
        ->assertJson([
            'success' => true,
            'data' => ['status' => 'published'],
        ]);

    expect($rundown->fresh()->published_at->toIsoString())->toBe($firstPublishedAt->toIsoString());
});

test('authenticated user can export rundowns', function () {
    Rundown::factory()->count(2)->create(['wedding_id' => $this->wedding->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson('/api/rundowns/export');

    $response->assertOk()
        ->assertJson(['success' => true])
        ->assertJsonStructure(['success', 'data']);
});

test('authenticated user can generate ai rundown', function () {
    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson('/api/rundowns/generate-ai');

    $response->assertCreated()
        ->assertJson(['success' => true]);

    expect(Rundown::count())->toBe(1);
    expect(RundownItem::count())->toBe(6);
});

test('user cannot access other users rundowns', function () {
    $otherUser = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $otherWedding = Wedding::factory()->create(['user_id' => $otherUser->id]);
    $rundown = Rundown::factory()->create(['wedding_id' => $otherWedding->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson('/api/rundowns/' . $rundown->uuid);

    $response->assertNotFound();
});

test('inactive user cannot manage rundowns', function () {
    $inactiveUser = User::factory()->create(['status' => User::STATUS_INACTIVE]);
    $token = $inactiveUser->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/rundowns');

    $response->assertForbidden();
});

test('authenticated user can filter rundowns by wedding', function () {
    Rundown::factory()->create(['wedding_id' => $this->wedding->id]);
    $otherWedding = Wedding::factory()->create(['user_id' => $this->user->id]);
    Rundown::factory()->create(['wedding_id' => $otherWedding->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson('/api/rundowns?wedding_id=' . $this->wedding->id);

    $response->assertOk()
        ->assertJson([
            'success' => true,
        ]);
});

test('authenticated user cannot create rundown for non-existent wedding', function () {
    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson('/api/rundowns', [
            'wedding_id' => 999,
            'title' => 'Test',
        ]);

    $response->assertStatus(422);
});
