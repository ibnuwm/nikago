<?php

declare(strict_types=1);

use App\Modules\Authentication\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authenticated user can get dashboard', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
    ]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/dashboard');

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                'user' => ['id', 'name', 'email'],
                'wedding',
                'subscription',
                'statistics' => [
                    'invitations_count',
                    'guests_count',
                    'rsvp_pending_count',
                    'rsvp_confirmed_count',
                    'budget_total',
                    'budget_spent',
                    'vendors_count',
                ],
                'reminders',
                'recent_activity',
                'upcoming_events' => [
                    'wedding_date',
                    'days_remaining',
                    'timeline_events',
                    'reminders',
                ],
            ],
        ]);
});

test('dashboard returns authenticated user data', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
    ]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/dashboard');

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $user->uuid,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
            ],
        ]);
});

test('unauthenticated user cannot get dashboard', function () {
    $response = $this->getJson('/api/dashboard');

    $response->assertUnauthorized();
});

test('inactive user cannot get dashboard', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_INACTIVE,
    ]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/dashboard');

    $response->assertForbidden();
});

test('suspended user cannot get dashboard', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_SUSPENDED,
    ]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/dashboard');

    $response->assertForbidden();
});

test('authenticated user can get dashboard statistics', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
    ]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/dashboard/statistics');

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => [
                'invitations_count' => 0,
                'guests_count' => 0,
                'rsvp_pending_count' => 0,
                'rsvp_confirmed_count' => 0,
                'budget_total' => 0,
                'budget_spent' => 0,
                'vendors_count' => 0,
            ],
        ]);
});

test('unauthenticated user cannot get statistics', function () {
    $response = $this->getJson('/api/dashboard/statistics');

    $response->assertUnauthorized();
});

test('authenticated user can get recent activity', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
    ]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/dashboard/recent-activity');

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => [],
        ]);
});

test('unauthenticated user cannot get recent activity', function () {
    $response = $this->getJson('/api/dashboard/recent-activity');

    $response->assertUnauthorized();
});

test('authenticated user can get upcoming events', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
    ]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/dashboard/upcoming-events');

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => [
                'wedding_date' => null,
                'days_remaining' => null,
                'timeline_events' => [],
                'reminders' => [],
            ],
        ]);
});

test('unauthenticated user cannot get upcoming events', function () {
    $response = $this->getJson('/api/dashboard/upcoming-events');

    $response->assertUnauthorized();
});
