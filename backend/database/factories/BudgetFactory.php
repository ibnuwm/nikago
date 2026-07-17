<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Modules\Budget\Models\Budget;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BudgetFactory extends Factory
{
    protected $model = Budget::class;

    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'tenant_id' => 1,
            'wedding_id' => 1,
            'title' => fake()->sentence(3),
            'description' => fake()->optional()->paragraph(),
            'total_budget' => fake()->randomFloat(2, 1000000, 50000000),
        ];
    }
}
