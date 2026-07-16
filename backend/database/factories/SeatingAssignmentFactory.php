<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Modules\Seating\Models\SeatingAssignment;
use Illuminate\Database\Eloquent\Factories\Factory;

class SeatingAssignmentFactory extends Factory
{
    protected $model = SeatingAssignment::class;

    public function definition(): array
    {
        return [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'tenant_id' => 1,
            'table_id' => 1,
            'guest_id' => 1,
            'seat_number' => fake()->optional(0.5)->numberBetween(1, 12),
            'notes' => fake()->optional()->text(100),
        ];
    }
}
