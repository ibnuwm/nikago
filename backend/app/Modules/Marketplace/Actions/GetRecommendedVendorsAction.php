<?php

declare(strict_types=1);

namespace App\Modules\Marketplace\Actions;

use App\Modules\Marketplace\Resources\MarketplaceVendorResource;
use App\Modules\Vendor\Models\Vendor;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetRecommendedVendorsAction
{
    public function execute(int $limit = 10): AnonymousResourceCollection
    {
        $vendors = Vendor::query()
            ->active()
            ->verified(true)
            ->where('rating', '>=', 4)
            ->orderBy('rating', 'desc')
            ->orderBy('total_review', 'desc')
            ->limit($limit)
            ->with('services')
            ->get();

        return MarketplaceVendorResource::collection($vendors);
    }
}
