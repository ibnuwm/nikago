<?php

declare(strict_types=1);

use App\Modules\Authentication\Models\User;
use App\Modules\Wedding\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authenticated user can get planner dashboard', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
    ]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/planner');

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                'wedding',
                'progress' => [
                    'progress',
                    'completed_task',
                    'total_task',
                ],
                'summary' => [
                    'wedding_title',
                    'wedding_status',
                    'progress',
                    'completed_task',
                    'total_task',
                    'guests_count',
                    'checklist_count',
                    'budget_total',
                    'budget_spent',
                    'timeline_count',
                    'reminder_count',
                ],
            ],
        ]);
});

test('authenticated user can get planner dashboard with wedding', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
    ]);
    $token = $user->createToken('auth-token')->plainTextToken;

    Wedding::factory()->create([
        'user_id' => $user->id,
        'title' => 'My Wedding',
        'status' => 'draft',
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/planner');

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => [
                'summary' => [
                    'wedding_title' => 'My Wedding',
                    'wedding_status' => 'draft',
                ],
            ],
        ]);
});

test('unauthenticated user cannot get planner dashboard', function () {
    $response = $this->getJson('/api/planner');

    $response->assertUnauthorized();
});

test('inactive user cannot get planner dashboard', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_INACTIVE,
    ]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/planner');

    $response->assertForbidden();
});

test('authenticated user can get planner summary', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
    ]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/planner/summary');

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => [
                'progress' => 0,
                'completed_task' => 0,
                'total_task' => 0,
                'guests_count' => 0,
                'checklist_count' => 0,
                'budget_total' => 0,
                'budget_spent' => 0,
                'timeline_count' => 0,
                'reminder_count' => 0,
            ],
        ]);
});

test('unauthenticated user cannot get planner summary', function () {
    $response = $this->getJson('/api/planner/summary');

    $response->assertUnauthorized();
});

test('authenticated user can get planner progress', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
    ]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/planner/progress');

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => [
                'progress' => 0,
                'completed_task' => 0,
                'total_task' => 0,
            ],
        ]);
});

test('unauthenticated user cannot get planner progress', function () {
    $response = $this->getJson('/api/planner/progress');

    $response->assertUnauthorized();
});

test('authenticated user can generate ai planner', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
    ]);
    $token = $user->createToken('auth-token')->plainTextToken;

    Wedding::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/planner/generate-ai');

    $response->assertOk()
        ->assertJson([
            'success' => true,
        ]);
});

test('authenticated user can get planner export', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
    ]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/planner/export');

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                'wedding_title',
                'exported_at',
                'progress' => [
                    'progress',
                    'completed_task',
                    'total_task',
                ],
                'summary' => [
                    'wedding_title',
                    'wedding_status',
                    'progress',
                    'completed_task',
                    'total_task',
                ],
            ],
        ]);
});
