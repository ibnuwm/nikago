<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Modules\Authentication\Models\User;
use App\Modules\CMS\Models\BlogCategory;
use App\Modules\CMS\Models\BlogPost;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BlogPostFactory extends Factory
{
    protected $model = BlogPost::class;

    public function definition(): array
    {
        $title = fake()->sentence();

        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . fake()->unique()->randomNumber(4),
            'excerpt' => fake()->paragraph(),
            'content' => fake()->paragraphs(5, true),
            'featured_image' => fake()->imageUrl(),
            'author_id' => User::factory(),
            'category_id' => null,
            'status' => 'draft',
            'published_at' => null,
            'seo_title' => $title,
            'seo_description' => fake()->sentence(),
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => 'published',
            'published_at' => fake()->dateTimeThisYear(),
        ]);
    }

    public function withCategory(): static
    {
        return $this->state(fn (array $attributes): array => [
            'category_id' => BlogCategory::factory(),
        ]);
    }
}
