<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Modules\Guest\Models\Guest;
use Illuminate\Database\Eloquent\Factories\Factory;

class GuestFactory extends Factory
{
    protected $model = Guest::class;

    public function definition(): array
    {
        $name = fake()->name();

        return [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'tenant_id' => 1,
            'wedding_id' => 1,
            'name' => $name,
            'phone' => fake()->phoneNumber(),
            'email' => fake()->optional()->safeEmail(),
            'address' => fake()->optional()->address(),
            'pax' => fake()->numberBetween(1, 5),
            'qr_code' => (string) \Illuminate\Support\Str::uuid(),
            'status' => Guest::STATUS_ACTIVE,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => Guest::STATUS_INACTIVE,
        ]);
    }
}
