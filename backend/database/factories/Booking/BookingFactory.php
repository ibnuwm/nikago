<?php

declare(strict_types=1);

namespace Database\Factories\Booking;

use App\Modules\Booking\Models\Booking;
use App\Modules\Vendor\Models\Vendor;
use App\Modules\Vendor\Models\VendorPackage;
use App\Modules\Wedding\Models\Wedding;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        $vendor = Vendor::factory()->create();
        $package = VendorPackage::query()->create([
            'vendor_id' => $vendor->id,
            'name' => 'Paket ' . $this->faker->word(),
            'price' => 5000000,
        ]);

        return [
            'tenant_id' => 1,
            'user_id' => 1,
            'wedding_id' => Wedding::factory(),
            'vendor_id' => $vendor->id,
            'package_id' => $package->id,
            'booking_date' => now()->toDateString(),
            'event_date' => now()->addMonths(3)->toDateString(),
            'subtotal' => 5000000,
            'discount' => 0,
            'total' => 5000000,
            'status' => 'pending',
        ];
    }
}
