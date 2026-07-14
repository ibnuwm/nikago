<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Modules\Invitation\Models\Invitation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class InvitationFactory extends Factory
{
    protected $model = Invitation::class;

    public function definition(): array
    {
        $title = fake()->sentence(3);

        return [
            'tenant_id' => 1,
            'wedding_id' => 1,
            'template_id' => 1,
            'theme_id' => null,
            'title' => $title,
            'slug' => Str::slug($title),
            'cover_image' => null,
            'description' => fake()->paragraph(),
            'status' => Invitation::STATUS_DRAFT,
            'published_at' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => Invitation::STATUS_PUBLISHED,
            'published_at' => now(),
        ]);
    }
}
