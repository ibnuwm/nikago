<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Actions;

use App\Modules\Vendor\Models\Vendor;
use App\Modules\Vendor\Resources\VendorPortfolioResource;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetVendorPortfoliosAction
{
    public function execute(Authenticatable $user, string $vendorUuid): AnonymousResourceCollection
    {
        $vendor = Vendor::query()->where('uuid', $vendorUuid)->firstOrFail();

        return VendorPortfolioResource::collection($vendor->portfolios);
    }
}
