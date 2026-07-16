<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Actions;

use App\Modules\Vendor\Models\Vendor;
use App\Modules\Vendor\Resources\VendorPackageResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetVendorPackagesAction
{
    public function execute(string $vendorUuid): AnonymousResourceCollection
    {
        $vendor = Vendor::query()->where('uuid', $vendorUuid)->firstOrFail();

        return VendorPackageResource::collection($vendor->packages()->orderBy('sort_order')->get());
    }
}
