<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Modules\Invitation\Models\InvitationTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class InvitationTemplateFactory extends Factory
{
    protected $model = InvitationTemplate::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'category' => fake()->randomElement(['general', 'modern', 'traditional', 'minimalist', 'elegant']),
            'description' => fake()->sentence(),
            'image' => null,
            'preview_image' => null,
            'is_active' => true,
            'is_premium' => false,
            'sort_order' => 0,
            'favorites_count' => 0,
        ];
    }

    public function premium(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_premium' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_active' => false,
        ]);
    }
}
