<?php

declare(strict_types=1);

namespace App\Modules\Marketplace\Actions;

use App\Modules\Marketplace\Models\Wishlist;
use App\Modules\Vendor\Models\Vendor;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;

class AddToWishlistAction
{
    public function execute(Authenticatable $user, string $vendorUuid): JsonResponse
    {
        $vendor = Vendor::query()->where('uuid', $vendorUuid)->firstOrFail();

        $exists = Wishlist::query()
            ->where('user_id', $user->id)
            ->where('vendor_id', $vendor->id)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => true,
                'message' => 'Vendor already in wishlist',
            ]);
        }

        Wishlist::query()->create([
            'tenant_id' => $user->tenant_id ?? 1,
            'user_id' => $user->id,
            'vendor_id' => $vendor->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Added to wishlist',
        ], 201);
    }
}
