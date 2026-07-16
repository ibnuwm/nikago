<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Actions;

use App\Modules\Vendor\Models\Vendor;
use App\Modules\Vendor\Models\VendorGallery;
use App\Modules\Vendor\Requests\UpdateVendorGalleryRequest;
use App\Modules\Vendor\Resources\VendorGalleryResource;
use Illuminate\Contracts\Auth\Authenticatable;

class UpdateVendorGalleryAction
{
    public function execute(UpdateVendorGalleryRequest $request, Authenticatable $user, string $vendorUuid, int $galleryId): VendorGalleryResource
    {
        Vendor::query()->forUser($user->id)->where('uuid', $vendorUuid)->firstOrFail();

        $gallery = VendorGallery::query()->findOrFail($galleryId);
        $gallery->update($request->validated());

        return new VendorGalleryResource($gallery->fresh());
    }
}
