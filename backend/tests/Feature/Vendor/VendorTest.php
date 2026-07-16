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
