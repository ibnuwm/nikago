<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Modules\CMS\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CmsPageFactory extends Factory
{
    protected $model = Page::class;

    public function definition(): array
    {
        $title = fake()->sentence();

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => fake()->paragraphs(3, true),
            'meta_title' => $title,
            'meta_description' => fake()->sentence(),
            'status' => 'draft',
            'is_published' => false,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => 'published',
            'is_published' => true,
        ]);
    }
}
