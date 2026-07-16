<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Actions;

use App\Modules\Vendor\Models\Vendor;
use App\Modules\Vendor\Resources\VendorServiceResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetVendorServicesAction
{
    public function execute(string $vendorUuid): AnonymousResourceCollection
    {
        $vendor = Vendor::query()->where('uuid', $vendorUuid)->firstOrFail();

        return VendorServiceResource::collection($vendor->services);
    }
}
