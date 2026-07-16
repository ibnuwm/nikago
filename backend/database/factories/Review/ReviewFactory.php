<?php

declare(strict_types=1);

namespace Database\Factories\Review;

use App\Modules\Booking\Models\Booking;
use App\Modules\Review\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'tenant_id' => 1,
            'user_id' => 1,
            'booking_id' => Booking::factory(),
            'vendor_id' => 1,
            'rating' => $this->faker->numberBetween(1, 5),
            'review' => $this->faker->paragraph(),
            'status' => 'approved',
        ];
    }
}
