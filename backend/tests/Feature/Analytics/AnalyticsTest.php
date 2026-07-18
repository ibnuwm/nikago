<?php

declare(strict_types=1);

use App\Modules\Authentication\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('unauthenticated user cannot access analytics', function () {
    $response = $this->getJson('/api/analytics/dashboard');

    $response->assertUnauthorized();
});

test('authenticated user can get dashboard analytics', function () {
    $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/analytics/dashboard');

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                'total_users',
                'active_users',
                'new_users',
                'total_vendors',
                'verified_vendors',
                'total_revenue',
                'mrr',
                'arr',
                'active_subscriptions',
                'total_ai_tokens',
                'total_ai_cost',
                'growth' => ['revenue', 'revenue_percentage'],
            ],
        ]);
});

test('authenticated user can get invitation analytics', function () {
    $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/analytics/invitations');

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => ['total_invitations', 'published', 'draft', 'by_status', 'trend'],
        ]);
});

test('authenticated user can get rsvp analytics', function () {
    $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/analytics/rsvp');

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                'total_guests', 'total_rsvps', 'confirmed', 'declined', 'maybe',
                'rsvp_rate', 'by_attendance', 'trend',
            ],
        ]);
});

test('authenticated user can get guest analytics', function () {
    $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/analytics/guests');

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => ['total_guests', 'invited', 'not_invited', 'by_status', 'trend'],
        ]);
});

test('authenticated user can get vendor analytics', function () {
    $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/analytics/vendors');

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                'total_vendors', 'active', 'inactive', 'verified',
                'featured', 'average_rating', 'new_vendors', 'vendor_density',
                'by_city', 'trend',
            ],
        ]);
});

test('authenticated user can get subscription analytics', function () {
    $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/analytics/subscriptions');

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                'total_subscriptions', 'active', 'expired', 'cancelled',
                'trialing', 'new_subscriptions', 'churn_rate',
                'mrr', 'arr', 'by_plan', 'trend',
            ],
        ]);
});

test('authenticated user can get revenue analytics', function () {
    $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/analytics/revenue');

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                'total_revenue', 'total_transactions', 'average_transaction_value',
                'growth_percentage', 'refunds', 'by_method', 'daily',
            ],
        ]);
});

test('authenticated user can get traffic analytics', function () {
    $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/analytics/traffic');

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                'page_views', 'unique_visitors', 'total_events',
                'by_event_type', 'daily',
            ],
        ]);
});

test('authenticated user can get ai analytics', function () {
    $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/analytics/ai');

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                'total_requests', 'total_tokens', 'total_cost',
                'average_tokens_per_request', 'average_cost_per_request',
                'by_feature', 'daily',
            ],
        ]);
});

test('authenticated user can export analytics', function () {
    $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/analytics/export');

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => ['report_id', 'type', 'format', 'headers', 'data', 'status'],
        ]);
});

test('analytics endpoints accept date filter', function () {
    $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/analytics/dashboard?start_date=2026-01-01&end_date=2026-12-31');

    $response->assertOk()
        ->assertJson(['success' => true]);
});

test('analytics endpoints return validation error for invalid date', function () {
    $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/analytics/dashboard?start_date=invalid-date');

    $response->assertStatus(422);
});
