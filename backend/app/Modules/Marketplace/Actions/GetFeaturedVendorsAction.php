<?php

declare(strict_types=1);

namespace App\Modules\Marketplace\Actions;

use App\Modules\Marketplace\Resources\MarketplaceVendorResource;
use App\Modules\Vendor\Models\Vendor;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetFeaturedVendorsAction
{
    public function execute(int $limit = 10): AnonymousResourceCollection
    {
        $vendors = Vendor::query()
            ->active()
            ->featured()
            ->orderBy('featured_at', 'desc')
            ->limit($limit)
            ->with('services')
            ->get();

        return MarketplaceVendorResource::collection($vendors);
    }
}
