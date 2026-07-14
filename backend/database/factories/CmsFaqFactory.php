<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Modules\CMS\Models\Faq;
use Illuminate\Database\Eloquent\Factories\Factory;

class CmsFaqFactory extends Factory
{
    protected $model = Faq::class;

    public function definition(): array
    {
        return [
            'question' => fake()->sentence(),
            'answer' => fake()->paragraph(),
            'category' => fake()->randomElement(['general', 'pricing', 'features', 'support']),
            'sort_order' => 0,
            'is_active' => true,
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_active' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_active' => false,
        ]);
    }
}
