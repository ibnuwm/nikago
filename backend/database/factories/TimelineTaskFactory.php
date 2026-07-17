<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Modules\Timeline\Models\TimelineTask;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TimelineTaskFactory extends Factory
{
    protected $model = TimelineTask::class;

    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'timeline_id' => 1,
            'title' => fake()->sentence(4),
            'description' => fake()->optional()->paragraph(),
            'priority' => fake()->randomElement(TimelineTask::PRIORITIES),
            'start_date' => fake()->optional()->date(),
            'due_date' => fake()->optional()->date(),
            'duration_days' => fake()->numberBetween(1, 14),
            'completed_at' => null,
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes): array => [
            'completed_at' => now(),
        ]);
    }
}
