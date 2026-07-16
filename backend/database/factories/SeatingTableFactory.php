<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Modules\Seating\Models\SeatingTable;
use Illuminate\Database\Eloquent\Factories\Factory;

class SeatingTableFactory extends Factory
{
    protected $model = SeatingTable::class;

    public function definition(): array
    {
        return [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'tenant_id' => 1,
            'wedding_id' => 1,
            'name' => fake()->word() . ' Table',
            'capacity' => fake()->numberBetween(4, 12),
            'shape' => fake()->randomElement(SeatingTable::SHAPES),
            'position_x' => fake()->optional(0.7)->numberBetween(0, 500),
            'position_y' => fake()->optional(0.7)->numberBetween(0, 500),
            'sort_order' => fake()->numberBetween(0, 50),
        ];
    }
}
