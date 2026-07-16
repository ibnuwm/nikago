<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Actions;

use App\Modules\Vendor\Models\Vendor;
use App\Modules\Vendor\Resources\VendorGalleryResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetVendorGalleriesAction
{
    public function execute(string $vendorUuid): AnonymousResourceCollection
    {
        $vendor = Vendor::query()->where('uuid', $vendorUuid)->firstOrFail();

        return VendorGalleryResource::collection($vendor->galleries()->orderBy('sort_order')->get());
    }
}
