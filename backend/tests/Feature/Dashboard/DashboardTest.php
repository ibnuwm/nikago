<?php

declare(strict_types=1);

use App\Modules\Authentication\Models\User;
use App\Modules\Guest\Models\Guest;
use App\Modules\RSVP\Models\Rsvp;
use App\Modules\Wedding\Models\Wedding;
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
                    'rsvp_total_guests',
                    'budget_total',
                    'budget_spent',
                    'vendors_count',
                    'checklist_progress',
                ],
                'reminders',
                'recent_activity',
                'upcoming_events' => [
                    'wedding_date',
                    'days_remaining',
                    'hours_remaining',
                    'phase',
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

test('dashboard returns null wedding when user has no wedding', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
    ]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/dashboard');

    $response->assertOk()
        ->assertJson([
            'data' => [
                'wedding' => null,
            ],
        ]);
});

test('dashboard returns wedding data when user has a wedding', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
    ]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $wedding = Wedding::factory()->create([
        'user_id' => $user->id,
        'wedding_date' => now()->addMonths(6),
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/dashboard');

    $response->assertOk()
        ->assertJson([
            'data' => [
                'wedding' => [
                    'id' => $wedding->id,
                    'title' => $wedding->title,
                    'wedding_date' => $wedding->wedding_date->format('Y-m-d'),
                ],
            ],
        ]);
});

test('dashboard returns real statistics with wedding', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
    ]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $wedding = Wedding::factory()->create([
        'user_id' => $user->id,
        'wedding_date' => now()->addMonths(6),
    ]);

    Guest::factory()->count(3)->create([
        'wedding_id' => $wedding->id,
    ]);

    $guest = Guest::factory()->create([
        'wedding_id' => $wedding->id,
    ]);

    Rsvp::factory()->create([
        'guest_id' => $guest->id,
        'attendance' => Rsvp::ATTENDANCE_YES,
        'total_guest' => 3,
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/dashboard');

    $response->assertOk()
        ->assertJson([
            'data' => [
                'statistics' => [
                    'guests_count' => 4,
                    'rsvp_confirmed_count' => 1,
                    'rsvp_total_guests' => 3,
                ],
            ],
        ]);
});

test('dashboard returns correct countdown for future wedding', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
    ]);
    $token = $user->createToken('auth-token')->plainTextToken;

    Wedding::factory()->create([
        'user_id' => $user->id,
        'wedding_date' => now()->addDays(100),
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/dashboard');

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                'upcoming_events' => [
                    'wedding_date',
                    'days_remaining',
                    'hours_remaining',
                    'phase',
                ],
            ],
        ]);

    $data = $response->json('data.upcoming_events');
    expect($data['days_remaining'])->toBeGreaterThanOrEqual(99);
    expect($data['days_remaining'])->toBeLessThanOrEqual(100);
    expect($data['phase'])->toBe('3-9 Months Out');
});

test('dashboard returns correct phase for different timeframes', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
    ]);
    $token = $user->createToken('auth-token')->plainTextToken;

    Wedding::factory()->create([
        'user_id' => $user->id,
        'wedding_date' => now()->addDays(200),
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/dashboard');

    $response->assertOk();

    $data = $response->json('data.upcoming_events');
    expect($data['phase'])->toBe('9-12 Months Out');
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
                'rsvp_total_guests' => 0,
                'budget_total' => 0,
                'budget_spent' => 0,
                'vendors_count' => 0,
                'checklist_progress' => 0,
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
                'hours_remaining' => null,
                'phase' => null,
                'timeline_events' => [],
                'reminders' => [],
            ],
        ]);
});

test('unauthenticated user cannot get upcoming events', function () {
    $response = $this->getJson('/api/dashboard/upcoming-events');

    $response->assertUnauthorized();
});
