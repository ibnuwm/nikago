<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Modules\Budget\Models\BudgetCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class BudgetCategoryFactory extends Factory
{
    protected $model = BudgetCategory::class;

    public function definition(): array
    {
        return [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'budget_id' => 1,
            'name' => fake()->randomElement(['Venue', 'Catering', 'Dekorasi', 'MUA', 'Dokumentasi']),
            'allocated_amount' => fake()->randomFloat(2, 500000, 10000000),
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
