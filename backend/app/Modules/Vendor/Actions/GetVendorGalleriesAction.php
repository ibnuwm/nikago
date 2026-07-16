<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Actions;

use App\Modules\Vendor\Models\Vendor;
use App\Modules\Vendor\Resources\VendorGalleryResource;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetVendorGalleriesAction
{
    public function execute(Authenticatable $user, string $vendorUuid): AnonymousResourceCollection
    {
        $vendor = Vendor::query()->where('uuid', $vendorUuid)->firstOrFail();

        $galleries = $vendor->galleries()->orderBy('sort_order')->get();

        return VendorGalleryResource::collection($galleries);
    }
}
