<?php

declare(strict_types=1);

use App\Modules\Authentication\Models\User;
use App\Modules\Checklist\Models\Checklist;
use App\Modules\Checklist\Models\ChecklistItem;
use App\Modules\Wedding\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $this->token = $this->user->createToken('auth-token')->plainTextToken;
    $this->wedding = Wedding::factory()->create(['user_id' => $this->user->id]);
});

test('authenticated user can list checklists', function () {
    Checklist::factory()->count(3)->create(['wedding_id' => $this->wedding->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson('/api/checklists');

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [],
            'meta' => ['current_page', 'last_page', 'per_page', 'total'],
        ]);
});

test('unauthenticated user cannot list checklists', function () {
    $response = $this->getJson('/api/checklists');
    $response->assertUnauthorized();
});

test('authenticated user can create checklist', function () {
    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson('/api/checklists', [
            'wedding_id' => $this->wedding->id,
            'title' => 'Test Checklist',
            'description' => 'Test description',
        ]);

    $response->assertCreated()
        ->assertJson([
            'success' => true,
            'data' => ['title' => 'Test Checklist', 'description' => 'Test description'],
        ]);
});

test('authenticated user can get checklist by uuid', function () {
    $checklist = Checklist::factory()->create(['wedding_id' => $this->wedding->id]);
    ChecklistItem::factory()->count(3)->create([
        'checklist_id' => $checklist->id,
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson('/api/checklists/' . $checklist->uuid);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => ['id' => $checklist->uuid],
        ]);
});

test('authenticated user can update checklist', function () {
    $checklist = Checklist::factory()->create(['wedding_id' => $this->wedding->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->putJson('/api/checklists/' . $checklist->uuid, [
            'title' => 'Updated Title',
        ]);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => ['title' => 'Updated Title'],
        ]);
});

test('authenticated user can delete checklist', function () {
    $checklist = Checklist::factory()->create(['wedding_id' => $this->wedding->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->deleteJson('/api/checklists/' . $checklist->uuid);

    $response->assertOk()
        ->assertJson(['success' => true]);

    $this->assertSoftDeleted($checklist);
});

test('authenticated user can complete checklist item', function () {
    $checklist = Checklist::factory()->create(['wedding_id' => $this->wedding->id]);
    $item = ChecklistItem::factory()->create(['checklist_id' => $checklist->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson('/api/checklists/' . $checklist->uuid . '/complete', [
            'item_uuid' => $item->uuid,
        ]);

    $response->assertOk()
        ->assertJson(['success' => true]);

    expect($item->fresh()->completed_at)->not->toBeNull();
});

test('authenticated user can uncomplete checklist item', function () {
    $checklist = Checklist::factory()->create(['wedding_id' => $this->wedding->id]);
    $item = ChecklistItem::factory()->completed()->create(['checklist_id' => $checklist->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson('/api/checklists/' . $checklist->uuid . '/uncomplete', [
            'item_uuid' => $item->uuid,
        ]);

    $response->assertOk()
        ->assertJson(['success' => true]);

    expect($item->fresh()->completed_at)->toBeNull();
});

test('authenticated user can reorder checklist items', function () {
    $checklist = Checklist::factory()->create(['wedding_id' => $this->wedding->id]);
    $item1 = ChecklistItem::factory()->create(['checklist_id' => $checklist->id, 'sort_order' => 0]);
    $item2 = ChecklistItem::factory()->create(['checklist_id' => $checklist->id, 'sort_order' => 1]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->patchJson('/api/checklists/' . $checklist->uuid . '/reorder', [
            'items' => [
                ['uuid' => $item1->uuid, 'sort_order' => 1],
                ['uuid' => $item2->uuid, 'sort_order' => 0],
            ],
        ]);

    $response->assertOk()
        ->assertJson(['success' => true]);

    expect($item1->fresh()->sort_order)->toBe(1);
    expect($item2->fresh()->sort_order)->toBe(0);
});

test('authenticated user can duplicate checklist', function () {
    $checklist = Checklist::factory()->create(['wedding_id' => $this->wedding->id]);
    ChecklistItem::factory()->count(2)->create(['checklist_id' => $checklist->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson('/api/checklists/' . $checklist->uuid . '/duplicate');

    $response->assertCreated()
        ->assertJson(['success' => true]);

    expect(Checklist::count())->toBe(2);
    expect(ChecklistItem::count())->toBe(4);
});

test('authenticated user can generate ai checklist', function () {
    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson('/api/checklists/generate-ai');

    $response->assertOk()
        ->assertJson(['success' => true]);

    expect(Checklist::count())->toBe(1);
    expect(ChecklistItem::count())->toBe(10);
});

test('user cannot access other users checklists', function () {
    $otherUser = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $otherWedding = Wedding::factory()->create(['user_id' => $otherUser->id]);
    $checklist = Checklist::factory()->create(['wedding_id' => $otherWedding->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson('/api/checklists/' . $checklist->uuid);

    $response->assertNotFound();
});

test('inactive user cannot manage checklists', function () {
    $inactiveUser = User::factory()->create(['status' => User::STATUS_INACTIVE]);
    $token = $inactiveUser->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/checklists');

    $response->assertForbidden();
});
