<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Modules\Wedding\Models\Wedding;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class WeddingFactory extends Factory
{
    protected $model = Wedding::class;

    public function definition(): array
    {
        $title = fake()->sentence(3);

        return [
            'tenant_id' => 1,
            'user_id' => 1,
            'title' => $title,
            'slug' => Str::slug($title),
            'status' => Wedding::STATUS_DRAFT,
            'theme' => null,
            'cover_image' => null,
            'published_at' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => Wedding::STATUS_PUBLISHED,
            'published_at' => now(),
        ]);
    }

    public function archived(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => Wedding::STATUS_ARCHIVED,
        ]);
    }
}
