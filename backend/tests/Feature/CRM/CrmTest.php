<?php

declare(strict_types=1);

use App\Modules\Authentication\Models\User;
use App\Modules\CRM\Models\Lead;
use App\Modules\CRM\Models\LeadActivity;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authenticated user can list leads', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    Lead::factory()->count(3)->create([
        'tenant_id' => 1,
        'user_id' => $user->id,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/crm/leads');

    $response->assertOk()->assertJson(['success' => true]);
});

test('unauthenticated user cannot list leads', function () {
    $response = $this->getJson('/api/crm/leads');

    $response->assertUnauthorized();
});

test('authenticated user can create lead', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/crm/leads', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '08123456789',
            'source' => 'website',
        ]);

    $response->assertCreated()->assertJson([
        'success' => true,
        'data' => ['name' => 'John Doe'],
    ]);

    $this->assertDatabaseHas('leads', ['email' => 'john@example.com']);
    $this->assertDatabaseHas('lead_activities', ['type' => 'created']);
});

test('authenticated user can get lead detail', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $lead = Lead::factory()->create([
        'tenant_id' => 1,
        'user_id' => $user->id,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson("/api/crm/leads/{$lead->uuid}");

    $response->assertOk()->assertJson([
        'success' => true,
        'data' => ['name' => $lead->name],
    ]);
});

test('authenticated user can update lead', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $lead = Lead::factory()->create([
        'tenant_id' => 1,
        'user_id' => $user->id,
        'stage' => 'new',
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->putJson("/api/crm/leads/{$lead->uuid}", [
            'name' => 'Updated Name',
            'stage' => 'contacted',
        ]);

    $response->assertOk()->assertJson([
        'success' => true,
        'data' => ['name' => 'Updated Name'],
    ]);

    $this->assertDatabaseHas('lead_activities', ['type' => 'stage_changed']);
});

test('authenticated user can move stage', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $lead = Lead::factory()->create([
        'tenant_id' => 1,
        'user_id' => $user->id,
        'stage' => 'new',
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->patchJson("/api/crm/leads/{$lead->uuid}/move-stage", [
            'stage' => 'negotiation',
        ]);

    $response->assertOk();

    $this->assertDatabaseHas('leads', ['uuid' => $lead->uuid, 'stage' => 'negotiation']);
});

test('authenticated user can add follow up', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $lead = Lead::factory()->create([
        'tenant_id' => 1,
        'user_id' => $user->id,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson("/api/crm/leads/{$lead->uuid}/follow-up", [
            'type' => 'call',
            'notes' => 'Called customer',
        ]);

    $response->assertOk();

    $this->assertDatabaseHas('lead_follow_ups', ['notes' => 'Called customer']);
    $this->assertDatabaseHas('lead_activities', ['type' => 'follow_up']);
});

test('authenticated user can get pipelines', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    Lead::factory()->count(2)->create([
        'tenant_id' => 1,
        'user_id' => $user->id,
        'stage' => 'new',
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/crm/pipelines');

    $response->assertOk()->assertJson(['success' => true]);
});

test('authenticated user can get statistics', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    Lead::factory()->create([
        'tenant_id' => 1,
        'user_id' => $user->id,
        'stage' => 'won',
        'deal_value' => 5000000,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/crm/statistics');

    $response->assertOk()->assertJson([
        'success' => true,
        'data' => ['total_leads' => 1, 'won' => 1],
    ]);
});

test('authenticated user can delete lead', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $lead = Lead::factory()->create([
        'tenant_id' => 1,
        'user_id' => $user->id,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->deleteJson("/api/crm/leads/{$lead->uuid}");

    $response->assertOk()->assertJson(['success' => true]);

    $this->assertDatabaseMissing('leads', ['uuid' => $lead->uuid]);
});
