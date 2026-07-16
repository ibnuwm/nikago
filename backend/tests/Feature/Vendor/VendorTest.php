<?php

declare(strict_types=1);

use App\Modules\Authentication\Models\User;
use App\Modules\Vendor\Models\Vendor;
use App\Modules\Vendor\Models\VendorService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $this->token = $this->user->createToken('auth-token')->plainTextToken;
});

test('authenticated user can create vendor', function () {
    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson('/api/vendors', [
            'business_name' => 'Wedding Decor',
            'description' => 'Best wedding decor in town',
            'phone' => '08123456789',
            'email' => 'decor@example.com',
            'city' => 'Jakarta',
            'province' => 'DKI Jakarta',
            'services' => [
                ['name' => 'Dekorasi', 'starting_price' => 5000000],
                ['name' => 'Lighting', 'starting_price' => 2000000],
            ],
        ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'success',
            'data' => [
                'id',
                'business_name',
                'slug',
                'services',
            ],
        ]);

    $this->assertDatabaseHas('vendors', [
        'business_name' => 'Wedding Decor',
    ]);

    $this->assertDatabaseHas('vendor_services', [
        'name' => 'Dekorasi',
    ]);
});

test('authenticated user can list vendors', function () {
    Vendor::factory()->count(3)->create();

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson('/api/vendors');

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

test('authenticated user can show vendor by uuid', function () {
    $vendor = Vendor::factory()->create();
    VendorService::factory()->create([
        'vendor_id' => $vendor->id,
        'name' => 'Dekorasi',
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson("/api/vendors/{$vendor->uuid}");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'data' => [
                'id',
                'business_name',
                'slug',
                'services' => [
                    '*' => ['name'],
                ],
            ],
        ]);
});

test('authenticated user can update vendor', function () {
    $vendor = Vendor::factory()->create(['user_id' => $this->user->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->putJson("/api/vendors/{$vendor->uuid}", [
            'business_name' => 'Updated Decor',
        ]);

    $response->assertStatus(200)
        ->assertJsonPath('data.business_name', 'Updated Decor');

    $this->assertDatabaseHas('vendors', [
        'id' => $vendor->id,
        'business_name' => 'Updated Decor',
    ]);
});

test('authenticated user can delete vendor', function () {
    $vendor = Vendor::factory()->create(['user_id' => $this->user->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->deleteJson("/api/vendors/{$vendor->uuid}");

    $response->assertStatus(200)
        ->assertJson(['success' => true]);

    $this->assertSoftDeleted('vendors', ['id' => $vendor->id]);
});

test('authenticated user can verify vendor', function () {
    $vendor = Vendor::factory()->unverified()->create();

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->patchJson("/api/vendors/{$vendor->uuid}/verify");

    $response->assertStatus(200);

    $this->assertDatabaseHas('vendors', [
        'id' => $vendor->id,
    ]);

    $this->assertNotNull($vendor->fresh()->verified_at);
});

test('authenticated user can activate vendor', function () {
    $vendor = Vendor::factory()->inactive()->create();

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->patchJson("/api/vendors/{$vendor->uuid}/activate");

    $response->assertStatus(200);

    $this->assertEquals('active', $vendor->fresh()->status);
});

test('authenticated user can deactivate vendor', function () {
    $vendor = Vendor::factory()->create();

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->patchJson("/api/vendors/{$vendor->uuid}/deactivate");

    $response->assertStatus(200);

    $this->assertEquals('inactive', $vendor->fresh()->status);
});

test('authenticated user can get vendor statistics', function () {
    $vendor = Vendor::factory()->create();
    VendorService::factory()->count(2)->create(['vendor_id' => $vendor->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson("/api/vendors/{$vendor->uuid}/statistics");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'data' => [
                'total_services',
                'total_packages',
                'total_portfolios',
                'total_galleries',
                'total_teams',
                'total_documents',
                'average_service_price',
                'rating',
                'total_review',
                'verified',
            ],
        ]);
});

test('authenticated user can search vendors', function () {
    Vendor::factory()->create(['business_name' => 'Jakarta Wedding']);
    Vendor::factory()->create(['business_name' => 'Bandung Catering']);
    Vendor::factory()->create(['business_name' => 'Jakarta Photographer']);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson('/api/vendors?search=Jakarta');

    $response->assertStatus(200);

    $data = $response->json('data');
    expect($data)->toHaveCount(2);
});

test('authenticated user can filter vendors by category', function () {
    $vendor1 = Vendor::factory()->create();
    $vendor2 = Vendor::factory()->create();

    VendorService::factory()->create([
        'vendor_id' => $vendor1->id,
        'name' => 'Dekorasi',
    ]);
    VendorService::factory()->create([
        'vendor_id' => $vendor2->id,
        'name' => 'Catering',
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson('/api/vendors?category=Dekorasi');

    $response->assertStatus(200);

    $data = $response->json('data');
    expect($data)->toHaveCount(1);
});

test('authenticated user can filter vendors by rating', function () {
    Vendor::factory()->create(['rating' => 4.5]);
    Vendor::factory()->create(['rating' => 2.0]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson('/api/vendors?min_rating=4');

    $response->assertStatus(200);

    $data = $response->json('data');
    expect($data)->toHaveCount(1);
});

test('authenticated user can filter verified vendors', function () {
    Vendor::factory()->verified()->create();
    Vendor::factory()->unverified()->create();

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson('/api/vendors?verified=1');

    $response->assertStatus(200);

    $data = $response->json('data');
    expect($data)->toHaveCount(1);
});

test('unauthenticated user cannot access vendors', function () {
    $response = $this->getJson('/api/vendors');

    $response->assertStatus(401);
});

test('user cannot update other user vendor', function () {
    $otherUser = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $vendor = Vendor::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->putJson("/api/vendors/{$vendor->uuid}", [
            'business_name' => 'Hacked Name',
        ]);

    $response->assertStatus(404);
});

test('authenticated user can update vendor profile with logo cover and hours', function () {
    $vendor = Vendor::factory()->create(['user_id' => $this->user->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->putJson("/api/vendors/{$vendor->uuid}", [
            'logo' => 'logos/logo.png',
            'cover' => 'covers/cover.jpg',
            'operating_hours' => [
                'monday' => ['open' => '09:00', 'close' => '17:00'],
                'tuesday' => ['open' => '09:00', 'close' => '17:00'],
            ],
            'social_media' => [
                'instagram' => 'https://instagram.com/vendor',
            ],
        ]);

    $response->assertStatus(200)
        ->assertJsonPath('data.logo', 'logos/logo.png')
        ->assertJsonPath('data.cover', 'covers/cover.jpg');

    $vendor->refresh();
    expect($vendor->operating_hours)->toBeArray();
    expect($vendor->social_media)->toBeArray();
    expect($vendor->social_media['instagram'])->toBe('https://instagram.com/vendor');
});

test('authenticated user can create gallery for their vendor', function () {
    $vendor = Vendor::factory()->create(['user_id' => $this->user->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson("/api/vendors/{$vendor->uuid}/galleries", [
            'image_url' => 'galleries/photo1.jpg',
            'caption' => 'Wedding decoration',
        ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'success',
            'data' => ['id', 'image_url', 'caption', 'sort_order'],
        ]);

    $this->assertDatabaseHas('vendor_galleries', [
        'vendor_id' => $vendor->id,
        'image_url' => 'galleries/photo1.jpg',
    ]);
});

test('authenticated user can list galleries', function () {
    $vendor = Vendor::factory()->create(['user_id' => $this->user->id]);
    $vendor->galleries()->createMany([
        ['image_url' => 'g1.jpg', 'sort_order' => 1],
        ['image_url' => 'g2.jpg', 'sort_order' => 2],
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson("/api/vendors/{$vendor->uuid}/galleries");

    $response->assertStatus(200)
        ->assertJsonCount(2, 'data');
});

test('authenticated user can update gallery', function () {
    $vendor = Vendor::factory()->create(['user_id' => $this->user->id]);
    $gallery = $vendor->galleries()->create(['image_url' => 'old.jpg']);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->putJson("/api/vendors/{$vendor->uuid}/galleries/{$gallery->id}", [
            'caption' => 'Updated caption',
        ]);

    $response->assertStatus(200)
        ->assertJsonPath('data.caption', 'Updated caption');
});

test('authenticated user can delete gallery', function () {
    $vendor = Vendor::factory()->create(['user_id' => $this->user->id]);
    $gallery = $vendor->galleries()->create(['image_url' => 'old.jpg']);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->deleteJson("/api/vendors/{$vendor->uuid}/galleries/{$gallery->id}");

    $response->assertStatus(200)->assertJson(['success' => true]);
    $this->assertDatabaseMissing('vendor_galleries', ['id' => $gallery->id]);
});

test('authenticated user can create portfolio for their vendor', function () {
    $vendor = Vendor::factory()->create(['user_id' => $this->user->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson("/api/vendors/{$vendor->uuid}/portfolios", [
            'title' => 'Wedding at Grand Ballroom',
            'description' => 'A beautiful wedding decoration',
            'image_url' => 'portfolios/wedding1.jpg',
        ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'success',
            'data' => ['id', 'title', 'image_url'],
        ]);

    $this->assertDatabaseHas('vendor_portfolios', [
        'vendor_id' => $vendor->id,
        'title' => 'Wedding at Grand Ballroom',
    ]);
});

test('authenticated user can list portfolios', function () {
    $vendor = Vendor::factory()->create(['user_id' => $this->user->id]);
    $vendor->portfolios()->createMany([
        ['title' => 'Project A', 'image_url' => 'a.jpg'],
        ['title' => 'Project B', 'image_url' => 'b.jpg'],
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson("/api/vendors/{$vendor->uuid}/portfolios");

    $response->assertStatus(200)
        ->assertJsonCount(2, 'data');
});

test('authenticated user can update portfolio', function () {
    $vendor = Vendor::factory()->create(['user_id' => $this->user->id]);
    $portfolio = $vendor->portfolios()->create(['title' => 'Old', 'image_url' => 'old.jpg']);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->putJson("/api/vendors/{$vendor->uuid}/portfolios/{$portfolio->id}", [
            'title' => 'Updated Portfolio',
        ]);

    $response->assertStatus(200)
        ->assertJsonPath('data.title', 'Updated Portfolio');
});

test('authenticated user can delete portfolio', function () {
    $vendor = Vendor::factory()->create(['user_id' => $this->user->id]);
    $portfolio = $vendor->portfolios()->create(['title' => 'Old', 'image_url' => 'old.jpg']);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->deleteJson("/api/vendors/{$vendor->uuid}/portfolios/{$portfolio->id}");

    $response->assertStatus(200)->assertJson(['success' => true]);
    $this->assertDatabaseMissing('vendor_portfolios', ['id' => $portfolio->id]);
});

test('authenticated user can create package for their vendor', function () {
    $vendor = Vendor::factory()->create(['user_id' => $this->user->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson("/api/vendors/{$vendor->uuid}/packages", [
            'name' => 'Gold Package',
            'description' => 'Complete wedding package',
            'price' => 25000000,
            'inclusions' => ['Dekorasi', 'Catering', 'Dokumentasi'],
        ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'success',
            'data' => ['id', 'name', 'price', 'inclusions'],
        ]);

    $this->assertDatabaseHas('vendor_packages', [
        'vendor_id' => $vendor->id,
        'name' => 'Gold Package',
    ]);
});

test('authenticated user can list packages', function () {
    $vendor = Vendor::factory()->create(['user_id' => $this->user->id]);
    $vendor->packages()->createMany([
        ['name' => 'Basic', 'price' => 10000000],
        ['name' => 'Premium', 'price' => 30000000],
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson("/api/vendors/{$vendor->uuid}/packages");

    $response->assertStatus(200)
        ->assertJsonCount(2, 'data');
});

test('authenticated user can update package', function () {
    $vendor = Vendor::factory()->create(['user_id' => $this->user->id]);
    $package = $vendor->packages()->create(['name' => 'Old', 'price' => 1000]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->putJson("/api/vendors/{$vendor->uuid}/packages/{$package->id}", [
            'price' => 5000000,
        ]);

    $response->assertStatus(200)
        ->assertJsonPath('data.price', 5000000);
});

test('authenticated user can delete package', function () {
    $vendor = Vendor::factory()->create(['user_id' => $this->user->id]);
    $package = $vendor->packages()->create(['name' => 'Old', 'price' => 1000]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->deleteJson("/api/vendors/{$vendor->uuid}/packages/{$package->id}");

    $response->assertStatus(200)->assertJson(['success' => true]);
    $this->assertDatabaseMissing('vendor_packages', ['id' => $package->id]);
});

test('authenticated user can create service for their vendor', function () {
    $vendor = Vendor::factory()->create(['user_id' => $this->user->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson("/api/vendors/{$vendor->uuid}/services", [
            'name' => 'Catering',
            'description' => 'Full catering service',
            'starting_price' => 15000000,
        ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'success',
            'data' => ['id', 'name', 'starting_price'],
        ]);

    $this->assertDatabaseHas('vendor_services', [
        'vendor_id' => $vendor->id,
        'name' => 'Catering',
    ]);
});

test('authenticated user can list services', function () {
    $vendor = Vendor::factory()->create(['user_id' => $this->user->id]);
    VendorService::factory()->count(2)->create(['vendor_id' => $vendor->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson("/api/vendors/{$vendor->uuid}/services");

    $response->assertStatus(200)
        ->assertJsonCount(2, 'data');
});

test('authenticated user can update service', function () {
    $vendor = Vendor::factory()->create(['user_id' => $this->user->id]);
    $service = VendorService::factory()->create(['vendor_id' => $vendor->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->putJson("/api/vendors/{$vendor->uuid}/services/{$service->id}", [
            'name' => 'Updated Service',
        ]);

    $response->assertStatus(200)
        ->assertJsonPath('data.name', 'Updated Service');
});

test('authenticated user can delete service', function () {
    $vendor = Vendor::factory()->create(['user_id' => $this->user->id]);
    $service = VendorService::factory()->create(['vendor_id' => $vendor->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->deleteJson("/api/vendors/{$vendor->uuid}/services/{$service->id}");

    $response->assertStatus(200)->assertJson(['success' => true]);
    $this->assertDatabaseMissing('vendor_services', ['id' => $service->id]);
});

test('user cannot manage other user galleries', function () {
    $otherUser = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $vendor = Vendor::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson("/api/vendors/{$vendor->uuid}/galleries", [
            'image_url' => 'hacked.jpg',
        ]);

    $response->assertStatus(404);
});

test('user cannot update gallery from other vendor using own vendor uuid', function () {
    $otherUser = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $ownVendor = Vendor::factory()->create(['user_id' => $this->user->id]);
    $otherVendor = Vendor::factory()->create(['user_id' => $otherUser->id]);
    $otherGallery = $otherVendor->galleries()->create(['image_url' => 'other.jpg']);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->putJson("/api/vendors/{$ownVendor->uuid}/galleries/{$otherGallery->id}", [
            'caption' => 'Should fail',
        ]);

    $response->assertStatus(404);
});

test('user cannot update portfolio from other vendor using own vendor uuid', function () {
    $otherUser = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $ownVendor = Vendor::factory()->create(['user_id' => $this->user->id]);
    $otherVendor = Vendor::factory()->create(['user_id' => $otherUser->id]);
    $otherPortfolio = $otherVendor->portfolios()->create(['title' => 'Other', 'image_url' => 'other.jpg']);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->putJson("/api/vendors/{$ownVendor->uuid}/portfolios/{$otherPortfolio->id}", [
            'title' => 'Should fail',
        ]);

    $response->assertStatus(404);
});

test('user cannot update package from other vendor using own vendor uuid', function () {
    $otherUser = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $ownVendor = Vendor::factory()->create(['user_id' => $this->user->id]);
    $otherVendor = Vendor::factory()->create(['user_id' => $otherUser->id]);
    $otherPackage = $otherVendor->packages()->create(['name' => 'Other', 'price' => 1000]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->putJson("/api/vendors/{$ownVendor->uuid}/packages/{$otherPackage->id}", [
            'name' => 'Should fail',
        ]);

    $response->assertStatus(404);
});

test('user cannot update service from other vendor using own vendor uuid', function () {
    $otherUser = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $ownVendor = Vendor::factory()->create(['user_id' => $this->user->id]);
    $otherVendor = Vendor::factory()->create(['user_id' => $otherUser->id]);
    $otherService = VendorService::factory()->create(['vendor_id' => $otherVendor->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->putJson("/api/vendors/{$ownVendor->uuid}/services/{$otherService->id}", [
            'name' => 'Should fail',
        ]);

    $response->assertStatus(404);
});
