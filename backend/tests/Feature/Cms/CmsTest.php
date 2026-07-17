<?php

declare(strict_types=1);

use App\Modules\CMS\Models\Banner;
use App\Modules\CMS\Models\Faq;
use App\Modules\CMS\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('anyone can get faqs', function () {
    Faq::factory()->count(3)->create([
        'is_active' => true,
    ]);

    $response = $this->getJson('/api/cms/faqs');

    $response->assertOk()
        ->assertJson([
            'success' => true,
        ])
        ->assertJsonCount(3, 'data');
});

test('inactive faqs are not returned', function () {
    Faq::factory()->active()->create();
    Faq::factory()->inactive()->create();

    $response = $this->getJson('/api/cms/faqs');

    $response->assertOk()
        ->assertJsonCount(1, 'data');
});

test('faqs are returned in sort order', function () {
    Faq::factory()->create(['sort_order' => 3, 'is_active' => true]);
    Faq::factory()->create(['sort_order' => 1, 'is_active' => true]);
    Faq::factory()->create(['sort_order' => 2, 'is_active' => true]);

    $response = $this->getJson('/api/cms/faqs');

    $response->assertOk();
    $data = $response->json('data');
    expect($data[0]['sort_order'])->toBe(1);
    expect($data[1]['sort_order'])->toBe(2);
    expect($data[2]['sort_order'])->toBe(3);
});

test('anyone can get banners', function () {
    Banner::factory()->count(2)->create([
        'is_active' => true,
    ]);

    $response = $this->getJson('/api/cms/banners');

    $response->assertOk()
        ->assertJson([
            'success' => true,
        ])
        ->assertJsonCount(2, 'data');
});

test('inactive banners are not returned', function () {
    Banner::factory()->active()->create();
    Banner::factory()->inactive()->create();

    $response = $this->getJson('/api/cms/banners');

    $response->assertOk()
        ->assertJsonCount(1, 'data');
});

test('anyone can get published pages', function () {
    Page::factory()->published()->count(2)->create();

    $response = $this->getJson('/api/cms/pages');

    $response->assertOk()
        ->assertJson([
            'success' => true,
        ])
        ->assertJsonCount(2, 'data');
});

test('draft pages are not returned', function () {
    Page::factory()->published()->create();
    Page::factory()->create(['is_published' => false, 'status' => 'draft']);

    $response = $this->getJson('/api/cms/pages');

    $response->assertOk()
        ->assertJsonCount(1, 'data');
});

test('anyone can get page by slug', function () {
    $page = Page::factory()->published()->create([
        'slug' => 'about-us',
    ]);

    $response = $this->getJson('/api/cms/pages/about-us');

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => [
                'id' => $page->uuid,
                'slug' => 'about-us',
            ],
        ]);
});

test('draft page is not accessible by slug', function () {
    Page::factory()->create([
        'slug' => 'draft-page',
        'is_published' => false,
        'status' => 'draft',
    ]);

    $response = $this->getJson('/api/cms/pages/draft-page');

    $response->assertNotFound();
});

test('nonexistent page returns 404', function () {
    $response = $this->getJson('/api/cms/pages/nonexistent');

    $response->assertNotFound();
});

test('invalid slug format returns 422', function () {
    $response = $this->getJson('/api/cms/pages/INVALID-SLUG-WITH-UPPERCASE');

    $response->assertStatus(422)
        ->assertJson([
            'success' => false,
            'message' => 'Invalid page slug format.',
        ]);
});

test('slug with special characters returns 422', function () {
    $response = $this->getJson('/api/cms/pages/invalid%20slug%21%40%23');

    $response->assertStatus(422);
});

test('anyone can get terms', function () {
    $page = Page::factory()->published()->terms()->create();

    $response = $this->getJson('/api/cms/terms');

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => [
                'id' => $page->uuid,
                'slug' => 'terms-of-service',
            ],
        ]);
});

test('terms returns 404 when not set', function () {
    $response = $this->getJson('/api/cms/terms');

    $response->assertNotFound();
});

test('unpublished terms are not returned', function () {
    Page::factory()->terms()->create(['is_published' => false]);

    $response = $this->getJson('/api/cms/terms');

    $response->assertNotFound();
});

test('anyone can get privacy policy', function () {
    $page = Page::factory()->published()->privacyPolicy()->create();

    $response = $this->getJson('/api/cms/privacy-policy');

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => [
                'id' => $page->uuid,
                'slug' => 'privacy-policy',
            ],
        ]);
});

test('privacy policy returns 404 when not set', function () {
    $response = $this->getJson('/api/cms/privacy-policy');

    $response->assertNotFound();
});

test('unpublished privacy policy is not returned', function () {
    Page::factory()->privacyPolicy()->create(['is_published' => false]);

    $response = $this->getJson('/api/cms/privacy-policy');

    $response->assertNotFound();
});

test('regular pages are not returned as terms', function () {
    Page::factory()->published()->create();

    $response = $this->getJson('/api/cms/terms');

    $response->assertNotFound();
});
