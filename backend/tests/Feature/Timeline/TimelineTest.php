<?php

declare(strict_types=1);

use App\Modules\Authentication\Models\User;
use App\Modules\Timeline\Models\Timeline;
use App\Modules\Timeline\Models\TimelineTask;
use App\Modules\Wedding\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $this->token = $this->user->createToken('auth-token')->plainTextToken;
    $this->wedding = Wedding::factory()->create(['user_id' => $this->user->id]);
});

test('authenticated user can list timelines', function () {
    Timeline::factory()->count(3)->create(['wedding_id' => $this->wedding->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson('/api/timelines');

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [],
            'meta' => ['current_page', 'last_page', 'per_page', 'total'],
        ]);
});

test('unauthenticated user cannot list timelines', function () {
    $response = $this->getJson('/api/timelines');
    $response->assertUnauthorized();
});

test('authenticated user can create timeline', function () {
    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson('/api/timelines', [
            'wedding_id' => $this->wedding->id,
            'title' => 'Test Timeline',
            'description' => 'Test description',
        ]);

    $response->assertCreated()
        ->assertJson([
            'success' => true,
            'data' => ['title' => 'Test Timeline', 'description' => 'Test description'],
        ]);
});

test('authenticated user can create timeline with tasks', function () {
    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson('/api/timelines', [
            'wedding_id' => $this->wedding->id,
            'title' => 'Timeline with Tasks',
            'tasks' => [
                ['title' => 'Task 1', 'priority' => 'high', 'sort_order' => 0],
                ['title' => 'Task 2', 'priority' => 'medium', 'sort_order' => 1],
            ],
        ]);

    $response->assertCreated()
        ->assertJson([
            'success' => true,
            'data' => ['title' => 'Timeline with Tasks'],
        ]);

    expect(TimelineTask::count())->toBe(2);
});

test('authenticated user can get timeline by uuid', function () {
    $timeline = Timeline::factory()->create(['wedding_id' => $this->wedding->id]);
    TimelineTask::factory()->count(3)->create([
        'timeline_id' => $timeline->id,
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson('/api/timelines/' . $timeline->uuid);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => ['id' => $timeline->uuid],
        ]);
});

test('authenticated user can update timeline', function () {
    $timeline = Timeline::factory()->create(['wedding_id' => $this->wedding->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->putJson('/api/timelines/' . $timeline->uuid, [
            'title' => 'Updated Title',
        ]);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => ['title' => 'Updated Title'],
        ]);
});

test('authenticated user can delete timeline', function () {
    $timeline = Timeline::factory()->create(['wedding_id' => $this->wedding->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->deleteJson('/api/timelines/' . $timeline->uuid);

    $response->assertOk()
        ->assertJson(['success' => true]);

    $this->assertSoftDeleted($timeline);
});

test('authenticated user can toggle timeline complete', function () {
    $timeline = Timeline::factory()->create(['wedding_id' => $this->wedding->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->patchJson('/api/timelines/' . $timeline->uuid . '/complete');

    $response->assertOk()
        ->assertJson(['success' => true]);

    expect($timeline->fresh()->completed_at)->not->toBeNull();
});

test('authenticated user can complete timeline task', function () {
    $timeline = Timeline::factory()->create(['wedding_id' => $this->wedding->id]);
    $task = TimelineTask::factory()->create(['timeline_id' => $timeline->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson('/api/timelines/' . $timeline->uuid . '/complete-task', [
            'task_uuid' => $task->uuid,
        ]);

    $response->assertOk()
        ->assertJson(['success' => true]);

    expect($task->fresh()->completed_at)->not->toBeNull();
});

test('authenticated user can uncomplete timeline task', function () {
    $timeline = Timeline::factory()->create(['wedding_id' => $this->wedding->id]);
    $task = TimelineTask::factory()->completed()->create(['timeline_id' => $timeline->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson('/api/timelines/' . $timeline->uuid . '/uncomplete-task', [
            'task_uuid' => $task->uuid,
        ]);

    $response->assertOk()
        ->assertJson(['success' => true]);

    expect($task->fresh()->completed_at)->toBeNull();
});

test('authenticated user can reorder timeline tasks', function () {
    $timeline = Timeline::factory()->create(['wedding_id' => $this->wedding->id]);
    $task1 = TimelineTask::factory()->create(['timeline_id' => $timeline->id, 'sort_order' => 0]);
    $task2 = TimelineTask::factory()->create(['timeline_id' => $timeline->id, 'sort_order' => 1]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->patchJson('/api/timelines/' . $timeline->uuid . '/reorder', [
            'tasks' => [
                ['uuid' => $task1->uuid, 'sort_order' => 1],
                ['uuid' => $task2->uuid, 'sort_order' => 0],
            ],
        ]);

    $response->assertOk()
        ->assertJson(['success' => true]);

    expect($task1->fresh()->sort_order)->toBe(1);
    expect($task2->fresh()->sort_order)->toBe(0);
});

test('authenticated user can generate ai timeline', function () {
    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson('/api/timelines/generate-ai');

    $response->assertCreated()
        ->assertJson(['success' => true]);

    expect(Timeline::count())->toBe(1);
    expect(TimelineTask::count())->toBe(10);
});

test('authenticated user can sync google calendar', function () {
    $timeline = Timeline::factory()->create(['wedding_id' => $this->wedding->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson('/api/timelines/' . $timeline->uuid . '/sync-google-calendar');

    $response->assertOk()
        ->assertJson(['success' => true, 'data' => ['id' => $timeline->uuid]]);
});

test('user cannot access other users timelines', function () {
    $otherUser = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $otherWedding = Wedding::factory()->create(['user_id' => $otherUser->id]);
    $timeline = Timeline::factory()->create(['wedding_id' => $otherWedding->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson('/api/timelines/' . $timeline->uuid);

    $response->assertNotFound();
});

test('inactive user cannot manage timelines', function () {
    $inactiveUser = User::factory()->create(['status' => User::STATUS_INACTIVE]);
    $token = $inactiveUser->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/timelines');

    $response->assertForbidden();
});
