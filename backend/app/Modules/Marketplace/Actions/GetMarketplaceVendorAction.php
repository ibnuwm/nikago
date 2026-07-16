<?php

declare(strict_types=1);

namespace App\Modules\Marketplace\Actions;

use App\Modules\Marketplace\Resources\MarketplaceVendorResource;
use App\Modules\Vendor\Models\Vendor;

class GetMarketplaceVendorAction
{
    public function execute(string $uuid, ?int $userId = null): MarketplaceVendorResource
    {
        $query = Vendor::query()
            ->active()
            ->where('uuid', $uuid)
            ->with(['services', 'packages', 'portfolios', 'galleries']);

        if ($userId !== null) {
            $query->with(['wishlists' => function ($q) use ($userId): void {
                $q->where('user_id', $userId);
            }]);
        }

        $vendor = $query->firstOrFail();

        return new MarketplaceVendorResource($vendor);
    }
}
