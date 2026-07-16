<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Actions;

use App\Modules\Vendor\Models\Vendor;
use App\Modules\Vendor\Models\VendorGallery;
use App\Modules\Vendor\Requests\StoreVendorGalleryRequest;
use App\Modules\Vendor\Resources\VendorGalleryResource;
use Illuminate\Contracts\Auth\Authenticatable;

class CreateVendorGalleryAction
{
    public function execute(StoreVendorGalleryRequest $request, Authenticatable $user, string $vendorUuid): VendorGalleryResource
    {
        $vendor = Vendor::query()->forUser($user->id)->where('uuid', $vendorUuid)->firstOrFail();

        $maxOrder = (int) VendorGallery::query()->where('vendor_id', $vendor->id)->max('sort_order');

        $gallery = VendorGallery::query()->create([
            'vendor_id' => $vendor->id,
            'image_url' => $request->input('image_url'),
            'caption' => $request->input('caption'),
            'sort_order' => $request->input('sort_order', $maxOrder + 1),
        ]);

        return new VendorGalleryResource($gallery);
    }
}
