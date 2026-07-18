<?php

declare(strict_types=1);

use App\Modules\Authentication\Models\User;
use App\Modules\Notification\Models\Notification;
use App\Modules\Notification\Models\NotificationTemplate;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authenticated user can list notifications', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    Notification::factory()->count(3)->create([
        'user_id' => $user->id,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/notifications');

    $response->assertOk();
});

test('unauthenticated user cannot list notifications', function () {
    $response = $this->getJson('/api/notifications');

    $response->assertUnauthorized();
});

test('authenticated user can get unread count', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    Notification::factory()->count(2)->create([
        'user_id' => $user->id,
        'is_read' => false,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/notifications/unread');

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => ['count' => 2],
        ]);
});

test('authenticated user can mark notification as read', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $notification = Notification::factory()->create([
        'user_id' => $user->id,
        'is_read' => false,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->patchJson("/api/notifications/{$notification->uuid}/read");

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => ['is_read' => true],
        ]);
});

test('authenticated user can mark all notifications as read', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    Notification::factory()->count(3)->create([
        'user_id' => $user->id,
        'is_read' => false,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->patchJson('/api/notifications/read-all');

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => ['updated' => 3],
        ]);

    $this->assertEquals(0, Notification::where('user_id', $user->id)->unread()->count());
});

test('authenticated user can delete notification', function () {
    $user = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $notification = Notification::factory()->create([
        'user_id' => $user->id,
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->deleteJson("/api/notifications/{$notification->uuid}");

    $response->assertOk()
        ->assertJson(['success' => true]);

    $this->assertDatabaseMissing('notifications', ['uuid' => $notification->uuid]);
});

test('user cannot see other user notifications', function () {
    $user1 = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $user2 = User::factory()->create([
        'status' => User::STATUS_ACTIVE,
        'tenant_id' => 1,
    ]);

    $notification = Notification::factory()->create([
        'user_id' => $user2->id,
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $user1->createToken('auth-token')->plainTextToken)
        ->patchJson("/api/notifications/{$notification->uuid}/read");

    $response->assertNotFound();
});
