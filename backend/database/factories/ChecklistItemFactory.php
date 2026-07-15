<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Modules\Checklist\Models\ChecklistItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChecklistItemFactory extends Factory
{
    protected $model = ChecklistItem::class;

    public function definition(): array
    {
        return [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'checklist_id' => 1,
            'title' => fake()->sentence(4),
            'priority' => fake()->randomElement(ChecklistItem::PRIORITIES),
            'due_date' => fake()->optional()->date(),
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
