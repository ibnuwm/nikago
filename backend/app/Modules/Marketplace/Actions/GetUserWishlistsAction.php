<?php

declare(strict_types=1);

namespace App\Modules\Marketplace\Actions;

use App\Modules\Marketplace\Models\Wishlist;
use App\Modules\Marketplace\Resources\MarketplaceVendorResource;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetUserWishlistsAction
{
    public function execute(Authenticatable $user): AnonymousResourceCollection
    {
        $items = Wishlist::query()
            ->where('user_id', $user->id)
            ->with('vendor.services')
            ->get();

        $vendors = $items->map(function ($item) {
            return $item->vendor;
        });

        return MarketplaceVendorResource::collection($vendors);
    }
}
