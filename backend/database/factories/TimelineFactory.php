<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Modules\Timeline\Models\Timeline;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TimelineFactory extends Factory
{
    protected $model = Timeline::class;

    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'tenant_id' => 1,
            'wedding_id' => 1,
            'title' => fake()->sentence(3),
            'description' => fake()->optional()->paragraph(),
            'progress' => 0,
            'completed_at' => null,
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes): array => [
            'completed_at' => now(),
            'progress' => 100,
        ]);
    }
}
