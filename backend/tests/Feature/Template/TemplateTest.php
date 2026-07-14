<?php

declare(strict_types=1);

use App\Modules\Authentication\Models\User;
use App\Modules\Invitation\Models\InvitationTemplate;
use App\Modules\Invitation\Models\InvitationTemplateFavorite;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authenticated user can get templates', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
    ]);

    InvitationTemplate::factory()->count(3)->create();

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/templates');

    $response->assertOk()
        ->assertJson([
            'success' => true,
        ])
        ->assertJsonCount(3, 'data');
});

test('unauthenticated user cannot get templates', function () {
    $response = $this->getJson('/api/templates');

    $response->assertUnauthorized();
});

test('authenticated user can get template by uuid', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
    ]);

    $template = InvitationTemplate::factory()->create();

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson("/api/templates/{$template->uuid}");

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => [
                'id' => $template->uuid,
                'name' => $template->name,
            ],
        ]);
});

test('inactive templates are not returned', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
    ]);

    InvitationTemplate::factory()->count(2)->create();
    InvitationTemplate::factory()->inactive()->create();

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/templates');

    $response->assertOk()
        ->assertJsonCount(2, 'data');
});

test('templates can be filtered by category', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
    ]);

    InvitationTemplate::factory()->count(2)->create(['category' => 'modern']);
    InvitationTemplate::factory()->count(3)->create(['category' => 'traditional']);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/templates?category=modern');

    $response->assertOk()
        ->assertJsonCount(2, 'data');
});

test('templates can be searched', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
    ]);

    InvitationTemplate::factory()->create(['name' => 'Summer Template']);
    InvitationTemplate::factory()->create(['name' => 'Winter Template']);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/templates?search=Summer');

    $response->assertOk()
        ->assertJsonCount(1, 'data');
});

test('authenticated user can get categories', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/templates/categories');

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => ['general', 'modern', 'traditional', 'minimalist', 'elegant'],
        ]);
});

test('authenticated user can get premium templates', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
    ]);

    InvitationTemplate::factory()->count(2)->create(['is_premium' => false]);
    InvitationTemplate::factory()->count(3)->premium()->create();

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/templates/premium');

    $response->assertOk()
        ->assertJsonCount(3, 'data');
});

test('authenticated user can use template', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
    ]);

    $template = InvitationTemplate::factory()->create();

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson("/api/templates/{$template->uuid}/use");

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => [
                'id' => $template->uuid,
            ],
        ]);
});

test('cannot use inactive template', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
    ]);

    $template = InvitationTemplate::factory()->inactive()->create();

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson("/api/templates/{$template->uuid}/use");

    $response->assertNotFound();
});

test('authenticated user can favorite template', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
    ]);

    $template = InvitationTemplate::factory()->create();

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson("/api/templates/{$template->uuid}/favorite");

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Template favorited successfully.',
        ]);

    $this->assertDatabaseHas('invitation_template_favorites', [
        'user_id' => $user->id,
        'template_id' => $template->id,
    ]);

    $this->assertDatabaseHas('invitation_templates', [
        'id' => $template->id,
        'favorites_count' => 1,
    ]);
});

test('authenticated user can unfavorite template', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
    ]);

    $template = InvitationTemplate::factory()->create();

    InvitationTemplateFavorite::create([
        'user_id' => $user->id,
        'template_id' => $template->id,
    ]);

    $template->update(['favorites_count' => 1]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->deleteJson("/api/templates/{$template->uuid}/favorite");

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Template unfavorited successfully.',
        ]);

    $this->assertDatabaseMissing('invitation_template_favorites', [
        'user_id' => $user->id,
        'template_id' => $template->id,
    ]);

    $this->assertDatabaseHas('invitation_templates', [
        'id' => $template->id,
        'favorites_count' => 0,
    ]);
});

test('favorite is idempotent', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
    ]);

    $template = InvitationTemplate::factory()->create();

    $token = $user->createToken('auth-token')->plainTextToken;

    $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson("/api/templates/{$template->uuid}/favorite");

    $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson("/api/templates/{$template->uuid}/favorite");

    $this->assertDatabaseHas('invitation_templates', [
        'id' => $template->id,
        'favorites_count' => 1,
    ]);
});

test('inactive user cannot get templates', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_INACTIVE,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/templates');

    $response->assertForbidden();
});

test('suspended user cannot get templates', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_SUSPENDED,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/templates');

    $response->assertForbidden();
});

test('invalid uuid returns 404', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/templates/invalid-uuid');

    $response->assertNotFound();
});

test('templates can be sorted by favorites_count', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
    ]);

    InvitationTemplate::factory()->create(['favorites_count' => 10]);
    InvitationTemplate::factory()->create(['favorites_count' => 5]);
    InvitationTemplate::factory()->create(['favorites_count' => 20]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/templates?sort=favorites_count&direction=desc');

    $response->assertOk()
        ->assertJsonCount(3, 'data');

    $data = $response->json('data');
    $this->assertEquals(20, $data[0]['favorites_count']);
    $this->assertEquals(10, $data[1]['favorites_count']);
    $this->assertEquals(5, $data[2]['favorites_count']);
});

test('unfavorite when not favorited does not decrement below zero', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
    ]);

    $template = InvitationTemplate::factory()->create(['favorites_count' => 0]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->deleteJson("/api/templates/{$template->uuid}/favorite");

    $response->assertOk();

    $this->assertDatabaseHas('invitation_templates', [
        'id' => $template->id,
        'favorites_count' => 0,
    ]);
});

test('templates can be searched with underscore treated as literal', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
    ]);

    InvitationTemplate::factory()->create(['name' => 'Test Template']);
    InvitationTemplate::factory()->create(['name' => 'Test_Template']);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/templates?search=Test_Template');

    $response->assertOk()
        ->assertJsonCount(1, 'data');
});

test('inactive template can be favorited', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
    ]);

    $template = InvitationTemplate::factory()->inactive()->create();

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson("/api/templates/{$template->uuid}/favorite");

    $response->assertOk()
        ->assertJson([
            'success' => true,
        ]);

    $this->assertDatabaseHas('invitation_template_favorites', [
        'user_id' => $user->id,
        'template_id' => $template->id,
    ]);
});

test('favorite template not found returns 404', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/templates/00000000-0000-0000-0000-000000000000/favorite');

    $response->assertNotFound();
});

test('unfavorite template not found returns 404', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->deleteJson('/api/templates/00000000-0000-0000-0000-000000000000/favorite');

    $response->assertNotFound();
});

test('suspended user cannot favorite template', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_SUSPENDED,
    ]);

    $template = InvitationTemplate::factory()->create();

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson("/api/templates/{$template->uuid}/favorite");

    $response->assertForbidden();
});
