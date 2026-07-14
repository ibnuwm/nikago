<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Modules\CMS\Models\Banner;
use Illuminate\Database\Eloquent\Factories\Factory;

class CmsBannerFactory extends Factory
{
    protected $model = Banner::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'subtitle' => fake()->sentence(),
            'image' => null,
            'link' => null,
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
