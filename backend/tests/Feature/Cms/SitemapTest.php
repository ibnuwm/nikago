<?php

declare(strict_types=1);

use App\Modules\CMS\Models\BlogPost;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('sitemap returns static urls', function () {
    $response = $this->getJson('/api/cms/sitemap');

    $response->assertOk()
        ->assertJson([
            'success' => true,
        ]);

    $data = $response->json('data');

    expect($data)->toBeArray();

    $paths = array_column($data, 'loc');
    expect($paths)->toContain('/');
    expect($paths)->toContain('/login');
    expect($paths)->toContain('/register');
    expect($paths)->toContain('/blog');
});

test('sitemap includes published blog posts', function () {
    BlogPost::factory()->published()->create(['slug' => 'test-article']);
    BlogPost::factory()->published()->create(['slug' => 'another-article']);

    $response = $this->getJson('/api/cms/sitemap');

    $response->assertOk();
    $data = $response->json('data');
    $paths = array_column($data, 'loc');

    expect($paths)->toContain('/blog/test-article');
    expect($paths)->toContain('/blog/another-article');
});

test('sitemap excludes draft blog posts', function () {
    BlogPost::factory()->create(['slug' => 'draft-article', 'status' => 'draft']);

    $response = $this->getJson('/api/cms/sitemap');

    $response->assertOk();
    $data = $response->json('data');
    $paths = array_column($data, 'loc');

    expect($paths)->not->toContain('/blog/draft-article');
});

test('sitemap includes priority and changefreq for each url', function () {
    $response = $this->getJson('/api/cms/sitemap');

    $response->assertOk();
    $data = $response->json('data');

    foreach ($data as $url) {
        expect($url)->toHaveKey('loc');
        expect($url)->toHaveKey('priority');
        expect($url)->toHaveKey('changefreq');
    }
});

test('sitemap homepage has highest priority', function () {
    $response = $this->getJson('/api/cms/sitemap');

    $response->assertOk();
    $data = $response->json('data');

    $home = collect($data)->firstWhere('loc', '/');
    expect($home['priority'])->toBe('1.0');
});
