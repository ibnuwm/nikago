<?php

declare(strict_types=1);

use App\Modules\Authentication\Models\User;
use App\Modules\Booking\Models\Booking;
use App\Modules\Review\Models\Review;
use App\Modules\Review\Models\ReviewImage;
use App\Modules\Vendor\Models\Vendor;
use App\Modules\Vendor\Models\VendorPackage;
use App\Modules\Wedding\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $this->token = $this->user->createToken('auth-token')->plainTextToken;
    $this->vendor = Vendor::factory()->create(['status' => 'active']);
    $this->package = VendorPackage::query()->create([
        'vendor_id' => $this->vendor->id,
        'name' => 'Paket Silver',
        'price' => 5000000,
    ]);
    $this->wedding = Wedding::factory()->create(['user_id' => $this->user->id]);
    $this->booking = Booking::factory()->create([
        'user_id' => $this->user->id,
        'vendor_id' => $this->vendor->id,
        'status' => 'completed',
    ]);
});

test('authenticated user can create review', function () {
    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson('/api/reviews', [
            'booking_uuid' => $this->booking->uuid,
            'rating' => 5,
            'review' => 'Excellent service!',
        ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'success',
            'data' => [
                'id',
                'rating',
                'review',
                'status',
            ],
        ]);

    expect($response->json('data.rating'))->toBe(5)
        ->and($response->json('data.review'))->toBe('Excellent service!')
        ->and($response->json('data.status'))->toBe('approved');
});

test('authenticated user can create review with images', function () {
    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson('/api/reviews', [
            'booking_uuid' => $this->booking->uuid,
            'rating' => 4,
            'review' => 'Great vendor!',
            'images' => [
                'https://example.com/photo1.jpg',
                'https://example.com/photo2.jpg',
            ],
        ]);

    $response->assertStatus(201);

    expect(ReviewImage::count())->toBe(2);
});

test('cannot create review for non-completed booking', function () {
    $pendingBooking = Booking::factory()->create([
        'user_id' => $this->user->id,
        'vendor_id' => $this->vendor->id,
        'status' => 'pending',
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson('/api/reviews', [
            'booking_uuid' => $pendingBooking->uuid,
            'rating' => 5,
            'review' => 'Great!',
        ]);

    $response->assertStatus(400);
});

test('cannot create duplicate review for same booking', function () {
    Review::factory()->create([
        'user_id' => $this->user->id,
        'booking_id' => $this->booking->id,
        'vendor_id' => $this->vendor->id,
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson('/api/reviews', [
            'booking_uuid' => $this->booking->uuid,
            'rating' => 4,
            'review' => 'Another review',
        ]);

    $response->assertStatus(409);
});

test('authenticated user can list their reviews', function () {
    Review::factory()->create([
        'user_id' => $this->user->id,
        'vendor_id' => $this->vendor->id,
        'booking_id' => $this->booking->id,
    ]);
    $otherUser = User::factory()->create();
    Review::factory()->count(2)->create([
        'user_id' => $otherUser->id,
        'vendor_id' => $this->vendor->id,
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson('/api/reviews');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'rating', 'status'],
            ],
        ]);

    expect($response->json('data'))->toHaveCount(1);
});

test('authenticated user can show review', function () {
    $review = Review::factory()->create([
        'user_id' => $this->user->id,
        'vendor_id' => $this->vendor->id,
        'booking_id' => $this->booking->id,
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson("/api/reviews/{$review->uuid}");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'data' => [
                'id',
                'rating',
                'review',
                'status',
            ],
        ]);
});

test('authenticated user can update review', function () {
    $review = Review::factory()->create([
        'user_id' => $this->user->id,
        'vendor_id' => $this->vendor->id,
        'booking_id' => $this->booking->id,
        'rating' => 3,
        'review' => 'Okay service.',
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->putJson("/api/reviews/{$review->uuid}", [
            'rating' => 5,
            'review' => 'Updated: Excellent service!',
        ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
        ]);

    expect($response->json('data.rating'))->toBe(5)
        ->and($response->json('data.review'))->toBe('Updated: Excellent service!');
});

test('authenticated user can delete review', function () {
    $review = Review::factory()->create([
        'user_id' => $this->user->id,
        'vendor_id' => $this->vendor->id,
        'booking_id' => $this->booking->id,
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->deleteJson("/api/reviews/{$review->uuid}");

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
        ]);

    expect(Review::where('uuid', $review->uuid)->exists())->toBeFalse();
});

test('vendor can reply to review', function () {
    $review = Review::factory()->create([
        'user_id' => $this->user->id,
        'vendor_id' => $this->vendor->id,
        'booking_id' => $this->booking->id,
    ]);
    $vendorUser = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $this->vendor->user_id = $vendorUser->id;
    $this->vendor->save();
    $vendorToken = $vendorUser->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $vendorToken)
        ->postJson("/api/reviews/{$review->uuid}/reply", [
            'reply' => 'Thank you for your feedback!',
        ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
        ]);

    expect($response->json('data.reply'))->toBe('Thank you for your feedback!')
        ->and($response->json('data.replied_at'))->not->toBeNull();
});

test('non-vendor cannot reply to review', function () {
    $otherVendor = Vendor::factory()->create(['status' => 'active', 'user_id' => User::factory()]);
    $otherPackage = VendorPackage::query()->create([
        'vendor_id' => $otherVendor->id,
        'name' => 'Paket Gold',
        'price' => 10000000,
    ]);
    $otherBooking = Booking::factory()->create([
        'user_id' => $this->user->id,
        'vendor_id' => $otherVendor->id,
        'status' => 'completed',
    ]);
    $review = Review::factory()->create([
        'user_id' => $this->user->id,
        'vendor_id' => $otherVendor->id,
        'booking_id' => $otherBooking->id,
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson("/api/reviews/{$review->uuid}/reply", [
            'reply' => 'Thank you!',
        ]);

    $response->assertStatus(404);
});

test('authenticated user can report review', function () {
    $review = Review::factory()->create([
        'user_id' => $this->user->id,
        'vendor_id' => $this->vendor->id,
        'booking_id' => $this->booking->id,
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson("/api/reviews/{$review->uuid}/report", [
            'reason' => 'Inappropriate content',
        ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'success',
            'data' => [
                'id',
                'reason',
                'status',
            ],
        ]);

    expect($response->json('data.status'))->toBe('pending');
});

test('anyone can get vendor reviews', function () {
    $review = Review::factory()->create([
        'user_id' => $this->user->id,
        'vendor_id' => $this->vendor->id,
        'booking_id' => $this->booking->id,
        'rating' => 5,
        'review' => 'Great vendor!',
    ]);

    $response = $this->getJson("/api/vendors/{$this->vendor->uuid}/reviews");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'rating', 'review', 'status'],
            ],
        ]);

    expect($response->json('data'))->toHaveCount(1)
        ->and($response->json('data.0.rating'))->toBe(5);
});

test('unauthenticated user cannot create review', function () {
    $this->postJson('/api/reviews', [
        'booking_uuid' => $this->booking->uuid,
        'rating' => 5,
    ])->assertStatus(401);
});

test('user cannot delete other user review', function () {
    $otherUser = User::factory()->create();
    $otherBooking = Booking::factory()->create([
        'user_id' => $otherUser->id,
        'vendor_id' => $this->vendor->id,
        'status' => 'completed',
    ]);
    $review = Review::factory()->create([
        'user_id' => $otherUser->id,
        'vendor_id' => $this->vendor->id,
        'booking_id' => $otherBooking->id,
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->deleteJson("/api/reviews/{$review->uuid}");

    $response->assertStatus(404);
});

test('rating updates automatically when review created', function () {
    $newVendor = Vendor::factory()->create([
        'status' => 'active',
        'rating' => 0,
        'total_review' => 0,
    ]);

    expect((float) $newVendor->fresh()->rating)->toBe(0.0)
        ->and($newVendor->fresh()->total_review)->toBe(0);

    $newBooking = Booking::factory()->create([
        'user_id' => $this->user->id,
        'vendor_id' => $newVendor->id,
        'status' => 'completed',
    ]);

    Review::factory()->create([
        'user_id' => $this->user->id,
        'vendor_id' => $newVendor->id,
        'booking_id' => $newBooking->id,
        'rating' => 5,
    ]);

    expect((float) $newVendor->fresh()->rating)->toBe(5.0)
        ->and($newVendor->fresh()->total_review)->toBe(1);
});

test('rating updates automatically when review deleted', function () {
    $newVendor = Vendor::factory()->create([
        'status' => 'active',
        'rating' => 0,
        'total_review' => 0,
    ]);

    $newBooking = Booking::factory()->create([
        'user_id' => $this->user->id,
        'vendor_id' => $newVendor->id,
        'status' => 'completed',
    ]);

    $review = Review::factory()->create([
        'user_id' => $this->user->id,
        'vendor_id' => $newVendor->id,
        'booking_id' => $newBooking->id,
        'rating' => 4,
    ]);

    expect((float) $newVendor->fresh()->rating)->toBe(4.0);
    expect($newVendor->fresh()->total_review)->toBe(1);

    $review->delete();

    expect((float) $newVendor->fresh()->rating)->toBe(0.0)
        ->and($newVendor->fresh()->total_review)->toBe(0);
});

test('review validation requires rating', function () {
    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson('/api/reviews', [
            'booking_uuid' => $this->booking->uuid,
            'review' => 'No rating',
        ]);

    $response->assertStatus(422);
});

test('review validation requires valid rating range', function () {
    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson('/api/reviews', [
            'booking_uuid' => $this->booking->uuid,
            'rating' => 6,
        ]);

    $response->assertStatus(422);
});
