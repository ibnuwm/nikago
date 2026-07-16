<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Modules\Vendor\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class VendorFactory extends Factory
{
    protected $model = Vendor::class;

    public function definition(): array
    {
        $businessName = fake()->company();

        return [
            'uuid' => (string) Str::uuid(),
            'tenant_id' => 1,
            'user_id' => 1,
            'business_name' => $businessName,
            'slug' => Str::slug($businessName) . '-' . Str::random(5),
            'description' => fake()->paragraph(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->companyEmail(),
            'address' => fake()->address(),
            'city' => fake()->city(),
            'province' => fake()->state(),
            'status' => 'active',
            'rating' => fake()->randomFloat(2, 1, 5),
            'total_review' => fake()->numberBetween(0, 100),
            'verified_at' => fake()->optional(0.7)->dateTime(),
        ];
    }

    public function verified(): static
    {
        return $this->state(fn (array $attributes): array => [
            'verified_at' => now(),
        ]);
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes): array => [
            'verified_at' => null,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => 'inactive',
        ]);
    }
}
