<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Modules\Rundown\Models\RundownItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RundownItemFactory extends Factory
{
    protected $model = RundownItem::class;

    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'rundown_id' => 1,
            'title' => fake()->sentence(3),
            'description' => fake()->optional()->paragraph(),
            'start_time' => fake()->optional()->time('H:i'),
            'end_time' => fake()->optional()->time('H:i'),
            'pic' => fake()->optional()->name(),
            'notes' => fake()->optional()->text(100),
            'sort_order' => fake()->numberBetween(0, 50),
        ];
    }
}
