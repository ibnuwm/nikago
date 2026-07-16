<?php

declare(strict_types=1);

namespace Database\Factories\Marketplace;

use App\Modules\Authentication\Models\User;
use App\Modules\Marketplace\Models\Wishlist;
use App\Modules\Vendor\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Wishlist>
 */
class WishlistFactory extends Factory
{
    protected $model = Wishlist::class;

    public function definition(): array
    {
        return [
            'tenant_id' => 1,
            'user_id' => User::factory(),
            'vendor_id' => Vendor::factory(),
        ];
    }
}
