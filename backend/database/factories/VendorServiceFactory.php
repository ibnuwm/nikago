<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Modules\Vendor\Models\Vendor;
use App\Modules\Vendor\Models\VendorService;
use Illuminate\Database\Eloquent\Factories\Factory;

class VendorServiceFactory extends Factory
{
    protected $model = VendorService::class;

    public function definition(): array
    {
        return [
            'vendor_id' => Vendor::factory(),
            'name' => fake()->randomElement(['Dekorasi', 'Catering', 'Venue', 'Fotografer', 'Videografer', 'MC', 'Entertainment']),
            'description' => fake()->optional()->sentence(),
            'starting_price' => fake()->optional()->randomFloat(2, 1000000, 50000000),
        ];
    }
}
