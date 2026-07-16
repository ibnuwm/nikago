<?php

declare(strict_types=1);

use App\Modules\Authentication\Models\User;
use App\Modules\Vendor\Models\Vendor;
use App\Modules\Vendor\Models\VendorPackage;
use App\Modules\Vendor\Models\VendorService;
use App\Modules\Marketplace\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $this->token = $this->user->createToken('auth-token')->plainTextToken;
});

test('anyone can list marketplace vendors', function () {
    Vendor::factory()->count(3)->create(['status' => 'active']);

    $response = $this->getJson('/api/marketplace/vendors');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'business_name',
                    'rating',
                ],
            ],
        ]);
    expect($response->json('data'))->toHaveCount(3);
});

test('anyone can show marketplace vendor detail', function () {
    $vendor = Vendor::factory()->create(['status' => 'active']);

    $response = $this->getJson("/api/marketplace/vendors/{$vendor->uuid}");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'data' => [
                'id',
                'business_name',
                'featured',
            ],
        ]);
    expect($response->json('data.id'))->toBe($vendor->uuid);
});

test('anyone can search marketplace vendors', function () {
    Vendor::factory()->create(['business_name' => 'Wedding Decor', 'status' => 'active']);
    Vendor::factory()->create(['business_name' => 'Photography Pro', 'status' => 'active']);

    $response = $this->getJson('/api/marketplace/search?search=Decor');

    $response->assertStatus(200);
    expect($response->json('data'))->toHaveCount(1)
        ->and($response->json('data.0.business_name'))->toBe('Wedding Decor');
});

test('anyone can get marketplace categories', function () {
    $vendor = Vendor::factory()->create(['status' => 'active']);
    VendorService::factory()->create(['vendor_id' => $vendor->id, 'name' => 'Dekorasi']);

    $response = $this->getJson('/api/marketplace/categories');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'name',
                    'vendor_count',
                ],
            ],
        ]);
    expect($response->json('data'))->toHaveCount(1)
        ->and($response->json('data.0.name'))->toBe('Dekorasi');
});

test('anyone can get popular vendors', function () {
    Vendor::factory()->create(['status' => 'active', 'verified_at' => now(), 'total_review' => 10, 'rating' => 4.5]);

    $response = $this->getJson('/api/marketplace/popular');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'business_name',
                    'rating',
                ],
            ],
        ]);
});

test('anyone can get recommended vendors', function () {
    Vendor::factory()->create(['status' => 'active', 'verified_at' => now(), 'rating' => 4.5, 'total_review' => 5]);

    $response = $this->getJson('/api/marketplace/recommended');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'business_name',
                    'rating',
                ],
            ],
        ]);
});

test('anyone can get featured vendors', function () {
    Vendor::factory()->create(['status' => 'active', 'featured' => true, 'featured_at' => now()]);

    $response = $this->getJson('/api/marketplace/featured');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'business_name',
                    'featured',
                ],
            ],
        ]);
    expect($response->json('data.0.featured'))->toBeTrue();
});

test('unauthenticated user cannot access wishlists', function () {
    $this->getJson('/api/marketplace/wishlists')->assertStatus(401);
});

test('authenticated user can manage wishlists', function () {
    $vendor = Vendor::factory()->create();

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson('/api/marketplace/wishlist', [
            'vendor_uuid' => $vendor->uuid,
        ]);

    $response->assertStatus(201)
        ->assertJson([
            'success' => true,
            'message' => 'Added to wishlist',
        ]);

    $this->assertDatabaseHas('wishlists', [
        'user_id' => $this->user->id,
        'vendor_id' => $vendor->id,
    ]);

    $listResponse = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson('/api/marketplace/wishlists');

    $listResponse->assertStatus(200);
    expect($listResponse->json('data'))->toHaveCount(1)
        ->and($listResponse->json('data.0.id'))->toBe($vendor->uuid);
});

test('authenticated user can remove from wishlist', function () {
    $vendor = Vendor::factory()->create();
    $wishlist = Wishlist::factory()->create([
        'user_id' => $this->user->id,
        'vendor_id' => $vendor->id,
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->deleteJson("/api/marketplace/wishlist/{$wishlist->uuid}");

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
        ]);

    $this->assertDatabaseMissing('wishlists', ['id' => $wishlist->id]);
});

test('user cannot remove other user wishlist', function () {
    $otherUser = User::factory()->create();
    $vendor = Vendor::factory()->create();
    $wishlist = Wishlist::factory()->create([
        'user_id' => $otherUser->id,
        'vendor_id' => $vendor->id,
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->deleteJson("/api/marketplace/wishlist/{$wishlist->uuid}");

    $response->assertStatus(404);
});

test('authenticated user can compare vendors', function () {
    $vendor1 = Vendor::factory()->create(['status' => 'active']);
    $vendor2 = Vendor::factory()->create(['status' => 'active']);
    VendorPackage::query()->create(['vendor_id' => $vendor1->id, 'name' => 'Paket Silver', 'price' => 5000000]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson('/api/marketplace/compare', [
            'vendor_uuids' => [$vendor1->uuid, $vendor2->uuid],
        ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'data' => [
                '*' => [
                    'id',
                    'business_name',
                    'packages',
                ],
            ],
        ]);
    expect($response->json('data'))->toHaveCount(2);
});

test('compare requires at least 2 vendors', function () {
    $vendor = Vendor::factory()->create(['status' => 'active']);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson('/api/marketplace/compare', [
            'vendor_uuids' => [$vendor->uuid],
        ]);

    $response->assertStatus(422);
});

test('unauthenticated user cannot compare vendors', function () {
    $response = $this->postJson('/api/marketplace/compare', [
        'vendor_uuids' => ['some-uuid'],
    ]);

    $response->assertStatus(401);
});

test('unauthenticated user cannot add to wishlist', function () {
    $response = $this->postJson('/api/marketplace/wishlist', [
        'vendor_uuid' => 'some-uuid',
    ]);

    $response->assertStatus(401);
});

test('marketplace only shows active vendors', function () {
    Vendor::factory()->create(['status' => 'inactive']);
    Vendor::factory()->create(['status' => 'active']);

    $response = $this->getJson('/api/marketplace/vendors');

    expect($response->json('data'))->toHaveCount(1);
});

test('marketplace vendor detail shows services and packages', function () {
    $vendor = Vendor::factory()->create(['status' => 'active']);
    VendorService::factory()->create(['vendor_id' => $vendor->id, 'name' => 'Dekorasi']);
    VendorPackage::query()->create(['vendor_id' => $vendor->id, 'name' => 'Paket Silver', 'price' => 5000000]);

    $response = $this->getJson("/api/marketplace/vendors/{$vendor->uuid}");

    $response->assertStatus(200);
    expect($response->json('data.services'))->toHaveCount(1)
        ->and($response->json('data.packages'))->toHaveCount(1);
});

test('marketplace can filter by city', function () {
    Vendor::factory()->create(['city' => 'Jakarta', 'status' => 'active']);
    Vendor::factory()->create(['city' => 'Bandung', 'status' => 'active']);

    $response = $this->getJson('/api/marketplace/vendors?city=Jakarta');

    expect($response->json('data'))->toHaveCount(1)
        ->and($response->json('data.0.city'))->toBe('Jakarta');
});

test('marketplace can filter by minimum rating', function () {
    Vendor::factory()->create(['rating' => 3, 'status' => 'active']);
    Vendor::factory()->create(['rating' => 4.5, 'status' => 'active']);

    $response = $this->getJson('/api/marketplace/vendors?min_rating=4');

    expect($response->json('data'))->toHaveCount(1);
});

test('authenticated user can see wishlist status on vendor detail', function () {
    $vendor = Vendor::factory()->create(['status' => 'active']);
    Wishlist::factory()->create([
        'user_id' => $this->user->id,
        'vendor_id' => $vendor->id,
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson("/api/marketplace/vendors/{$vendor->uuid}");

    $response->assertStatus(200);
    expect($response->json('data.is_wishlisted'))->toBeTrue();
});
