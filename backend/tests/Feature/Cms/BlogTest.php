<?php

declare(strict_types=1);

use App\Modules\Authentication\Models\User;
use App\Modules\CMS\Models\BlogCategory;
use App\Modules\CMS\Models\BlogPost;
use App\Modules\CMS\Models\BlogTag;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('anyone can get published blog posts', function () {
    BlogPost::factory()->published()->count(3)->create();

    $response = $this->getJson('/api/cms/blog/posts');

    $response->assertOk()
        ->assertJson([
            'success' => true,
        ])
        ->assertJsonCount(3, 'data');
});

test('draft blog posts are not returned', function () {
    BlogPost::factory()->published()->create();
    BlogPost::factory()->create(['status' => 'draft']);

    $response = $this->getJson('/api/cms/blog/posts');

    $response->assertOk()
        ->assertJsonCount(1, 'data');
});

test('scheduled blog posts are not returned', function () {
    BlogPost::factory()->published()->create();
    BlogPost::factory()->create(['status' => 'scheduled']);

    $response = $this->getJson('/api/cms/blog/posts');

    $response->assertOk()
        ->assertJsonCount(1, 'data');
});

test('blog posts are paginated', function () {
    BlogPost::factory()->published()->count(15)->create();

    $response = $this->getJson('/api/cms/blog/posts?per_page=5');

    $response->assertOk()
        ->assertJsonCount(5, 'data')
        ->assertJsonStructure([
            'meta' => [
                'current_page',
                'last_page',
                'per_page',
                'total',
            ],
        ]);
    expect($response->json('meta.total'))->toBe(15);
    expect($response->json('meta.per_page'))->toBe(5);
});

test('anyone can get blog post by slug', function () {
    $post = BlogPost::factory()->published()->create([
        'slug' => 'test-blog-post',
    ]);

    $response = $this->getJson('/api/cms/blog/posts/test-blog-post');

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => [
                'id' => $post->uuid,
                'slug' => 'test-blog-post',
            ],
        ]);
});

test('blog post response has expected structure', function () {
    $author = User::factory()->create();
    $category = BlogCategory::factory()->create();
    $tag = BlogTag::factory()->create();

    $post = BlogPost::factory()->published()->create([
        'author_id' => $author->id,
        'category_id' => $category->id,
        'title' => 'Sample Blog Post',
        'slug' => 'sample-blog-post',
        'excerpt' => 'Sample excerpt',
        'content' => 'Sample content',
        'featured_image' => 'http://example.com/image.jpg',
        'seo_title' => 'SEO Title',
        'seo_description' => 'SEO Description',
    ]);
    $post->tags()->attach($tag);

    $response = $this->getJson('/api/cms/blog/posts/sample-blog-post');

    $response->assertOk();
    $data = $response->json('data');

    expect($data['id'])->toBe($post->uuid);
    expect($data['title'])->toBe('Sample Blog Post');
    expect($data['slug'])->toBe('sample-blog-post');
    expect($data['excerpt'])->toBe('Sample excerpt');
    expect($data['content'])->toBe('Sample content');
    expect($data['featured_image'])->toBe('http://example.com/image.jpg');
    expect($data['author']['name'])->toBe($author->name);
    expect($data['category']['name'])->toBe($category->name);
    expect($data['tags'][0]['name'])->toBe($tag->name);
    expect($data['status'])->toBe('published');
    expect($data['seo_title'])->toBe('SEO Title');
    expect($data['seo_description'])->toBe('SEO Description');
    expect($data['published_at'])->not->toBeNull();
});

test('draft blog post is not accessible by slug', function () {
    BlogPost::factory()->create([
        'slug' => 'draft-post',
        'status' => 'draft',
    ]);

    $response = $this->getJson('/api/cms/blog/posts/draft-post');

    $response->assertNotFound();
});

test('nonexistent blog post returns 404', function () {
    $response = $this->getJson('/api/cms/blog/posts/nonexistent');

    $response->assertNotFound();
});

test('invalid blog slug format returns 422', function () {
    $response = $this->getJson('/api/cms/blog/posts/INVALID-SLUG');

    $response->assertStatus(422)
        ->assertJson([
            'success' => false,
            'message' => 'Invalid blog slug format.',
        ]);
});

test('blog posts can be searched by title', function () {
    BlogPost::factory()->published()->create(['title' => 'Wedding Dress Guide']);
    BlogPost::factory()->published()->create(['title' => 'Wedding Catering Tips']);
    BlogPost::factory()->published()->create(['title' => 'Honeymoon Destinations']);

    $response = $this->getJson('/api/cms/blog/posts?search=Wedding');

    $response->assertOk()
        ->assertJsonCount(2, 'data');
});

test('blog posts can be filtered by category', function () {
    $category = BlogCategory::factory()->create(['slug' => 'wedding-tips']);
    $otherCategory = BlogCategory::factory()->create(['slug' => 'real-weddings']);

    BlogPost::factory()->published()->withCategory()->create([
        'category_id' => $category->id,
    ]);
    BlogPost::factory()->published()->withCategory()->create([
        'category_id' => $otherCategory->id,
    ]);

    $response = $this->getJson('/api/cms/blog/posts?category=wedding-tips');

    $response->assertOk()
        ->assertJsonCount(1, 'data');
});

test('blog posts can be filtered by tag', function () {
    $tag = BlogTag::factory()->create(['slug' => 'photography']);
    $post = BlogPost::factory()->published()->create();
    $post->tags()->attach($tag);

    $otherPost = BlogPost::factory()->published()->create();
    $otherTag = BlogTag::factory()->create(['slug' => 'catering']);
    $otherPost->tags()->attach($otherTag);

    $response = $this->getJson('/api/cms/blog/posts?tag=photography');

    $response->assertOk()
        ->assertJsonCount(1, 'data');
});

test('anyone can get blog categories', function () {
    BlogCategory::factory()->count(3)->create();

    $response = $this->getJson('/api/cms/blog/categories');

    $response->assertOk()
        ->assertJson([
            'success' => true,
        ])
        ->assertJsonCount(3, 'data');
});

test('blog categories include post count', function () {
    $category = BlogCategory::factory()->create();
    BlogPost::factory()->published()->count(2)->create([
        'category_id' => $category->id,
    ]);

    $response = $this->getJson('/api/cms/blog/categories');

    $response->assertOk();
    expect($response->json('data.0.post_count'))->toBe(2);
});

test('anyone can get blog tags', function () {
    BlogTag::factory()->count(3)->create();

    $response = $this->getJson('/api/cms/blog/tags');

    $response->assertOk()
        ->assertJson([
            'success' => true,
        ])
        ->assertJsonCount(3, 'data');
});

test('blog tags include post count', function () {
    $tag = BlogTag::factory()->create();
    $post = BlogPost::factory()->published()->create();
    $post->tags()->attach($tag);

    $response = $this->getJson('/api/cms/blog/tags');

    $response->assertOk();
    expect($response->json('data.0.post_count'))->toBe(1);
});
