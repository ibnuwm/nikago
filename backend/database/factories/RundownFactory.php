<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Modules\Rundown\Models\Rundown;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RundownFactory extends Factory
{
    protected $model = Rundown::class;

    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'tenant_id' => 1,
            'wedding_id' => 1,
            'title' => fake()->sentence(3),
            'description' => fake()->optional()->paragraph(),
            'status' => Rundown::STATUS_DRAFT,
            'published_at' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => Rundown::STATUS_PUBLISHED,
            'published_at' => now(),
        ]);
    }
}
