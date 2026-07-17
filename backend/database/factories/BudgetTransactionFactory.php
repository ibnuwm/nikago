<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Modules\Budget\Models\BudgetTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BudgetTransactionFactory extends Factory
{
    protected $model = BudgetTransaction::class;

    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'category_id' => 1,
            'type' => fake()->randomElement(BudgetTransaction::TYPES),
            'amount' => fake()->randomFloat(2, 10000, 5000000),
            'description' => fake()->optional()->sentence(),
            'transaction_date' => fake()->date(),
        ];
    }
}
