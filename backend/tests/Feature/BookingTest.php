<?php

declare(strict_types=1);

use App\Modules\Authentication\Models\User;
use App\Modules\Booking\Models\Booking;
use App\Modules\Vendor\Models\Vendor;
use App\Modules\Vendor\Models\VendorPackage;
use App\Modules\Wedding\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $this->token = $this->user->createToken('auth-token')->plainTextToken;
    $this->wedding = Wedding::factory()->create(['user_id' => $this->user->id]);
    $this->vendor = Vendor::factory()->create(['status' => 'active']);
    $this->package = VendorPackage::query()->create([
        'vendor_id' => $this->vendor->id,
        'name' => 'Paket Silver',
        'price' => 5000000,
    ]);
});

test('authenticated user can create booking', function () {
    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson('/api/bookings', [
            'vendor_uuid' => $this->vendor->uuid,
            'package_id' => $this->package->id,
            'event_date' => now()->addMonth()->toDateString(),
            'wedding_id' => $this->wedding->id,
        ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'success',
            'data' => [
                'id',
                'status',
                'total',
                'vendor',
            ],
        ]);

    expect($response->json('data.status'))->toBe('pending')
        ->and((float) $response->json('data.total'))->toBe(5000000.0);
});

test('authenticated user can list bookings', function () {
    Booking::factory()->create(['user_id' => $this->user->id]);
    $otherUser = User::factory()->create();
    Booking::factory()->count(2)->create(['user_id' => $otherUser->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson('/api/bookings');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'status', 'total'],
            ],
        ]);

    expect($response->json('data'))->toHaveCount(1);
});

test('authenticated user can show booking', function () {
    $booking = Booking::factory()->create(['user_id' => $this->user->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson("/api/bookings/{$booking->uuid}");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'data' => [
                'id',
                'status',
                'vendor',
                'package',
            ],
        ]);
});

test('authenticated user can update booking', function () {
    $booking = Booking::factory()->create(['user_id' => $this->user->id]);
    $newDate = now()->addMonths(2)->toDateString();

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->putJson("/api/bookings/{$booking->uuid}", [
            'event_date' => $newDate,
            'notes' => 'Updated notes',
        ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
        ]);

    expect($response->json('data.notes'))->toBe('Updated notes');
});

test('authenticated user can confirm booking', function () {
    $booking = Booking::factory()->create([
        'user_id' => $this->user->id,
        'status' => 'pending',
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->patchJson("/api/bookings/{$booking->uuid}/confirm");

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
        ]);

    expect($response->json('data.status'))->toBe('confirmed');
});

test('authenticated user can complete booking', function () {
    $booking = Booking::factory()->create([
        'user_id' => $this->user->id,
        'status' => 'confirmed',
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->patchJson("/api/bookings/{$booking->uuid}/complete");

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
        ]);

    expect($response->json('data.status'))->toBe('completed');
});

test('authenticated user can cancel booking', function () {
    $booking = Booking::factory()->create([
        'user_id' => $this->user->id,
        'status' => 'pending',
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->patchJson("/api/bookings/{$booking->uuid}/cancel");

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
        ]);

    expect($response->json('data.status'))->toBe('cancelled');
});

test('cannot confirm non-pending booking', function () {
    $booking = Booking::factory()->create([
        'user_id' => $this->user->id,
        'status' => 'confirmed',
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->patchJson("/api/bookings/{$booking->uuid}/confirm");

    $response->assertStatus(422);
});

test('cannot complete non-confirmed booking', function () {
    $booking = Booking::factory()->create([
        'user_id' => $this->user->id,
        'status' => 'pending',
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->patchJson("/api/bookings/{$booking->uuid}/complete");

    $response->assertStatus(422);
});

test('authenticated user can upload contract', function () {
    $booking = Booking::factory()->create(['user_id' => $this->user->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson("/api/bookings/{$booking->uuid}/contract", [
            'file_url' => 'https://example.com/contract.pdf',
            'notes' => 'Contract document',
        ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'success',
            'data' => [
                'id',
                'type',
                'file_url',
            ],
        ]);
});

test('authenticated user can view booking history', function () {
    $booking = Booking::factory()->create(['user_id' => $this->user->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson("/api/bookings/history/{$booking->uuid}");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'status_to', 'created_at'],
            ],
        ]);
});

test('authenticated user can view booking calendar', function () {
    Booking::factory()->create([
        'user_id' => $this->user->id,
        'vendor_id' => $this->vendor->id,
        'event_date' => now()->addMonth()->toDateString(),
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson('/api/bookings/calendar?' . http_build_query([
            'year' => now()->addMonth()->year,
            'month' => now()->addMonth()->month,
        ]));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'event_date', 'status'],
            ],
        ]);
});

test('unauthenticated user cannot access bookings', function () {
    $this->getJson('/api/bookings')->assertStatus(401);
});

test('user cannot access other user booking', function () {
    $otherUser = User::factory()->create();
    $booking = Booking::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson("/api/bookings/{$booking->uuid}");

    $response->assertStatus(404);
});

test('create booking requires valid vendor', function () {
    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson('/api/bookings', [
            'vendor_uuid' => 'invalid-uuid',
            'package_id' => $this->package->id,
            'event_date' => now()->addMonth()->toDateString(),
            'wedding_id' => $this->wedding->id,
        ]);

    $response->assertStatus(422);
});

test('booking status flow is correct', function () {
    $booking = Booking::factory()->create([
        'user_id' => $this->user->id,
        'status' => 'pending',
    ]);

    $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->patchJson("/api/bookings/{$booking->uuid}/confirm")
        ->assertStatus(200);
    expect($booking->fresh()->status)->toBe('confirmed');

    $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->patchJson("/api/bookings/{$booking->uuid}/complete")
        ->assertStatus(200);
    expect($booking->fresh()->status)->toBe('completed');
});

test('calendar returns only non-cancelled bookings', function () {
    Booking::factory()->create([
        'user_id' => $this->user->id,
        'vendor_id' => $this->vendor->id,
        'status' => 'cancelled',
        'event_date' => now()->addMonth()->toDateString(),
    ]);
    Booking::factory()->create([
        'user_id' => $this->user->id,
        'vendor_id' => $this->vendor->id,
        'status' => 'confirmed',
        'event_date' => now()->addMonth()->toDateString(),
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson('/api/bookings/calendar?' . http_build_query([
            'year' => now()->addMonth()->year,
            'month' => now()->addMonth()->month,
        ]));

    expect($response->json('data'))->toHaveCount(1);
});

test('can filter bookings by status', function () {
    Booking::factory()->create([
        'user_id' => $this->user->id,
        'status' => 'pending',
    ]);
    Booking::factory()->create([
        'user_id' => $this->user->id,
        'status' => 'confirmed',
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson('/api/bookings?status=pending');

    expect($response->json('data'))->toHaveCount(1)
        ->and($response->json('data.0.status'))->toBe('pending');
});
