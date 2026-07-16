<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Actions;

use App\Modules\Vendor\Models\Vendor;
use App\Modules\Vendor\Models\VendorGallery;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;

class DeleteVendorGalleryAction
{
    public function execute(Authenticatable $user, string $vendorUuid, int $galleryId): JsonResponse
    {
        $vendor = Vendor::query()->forUser($user->id)->where('uuid', $vendorUuid)->firstOrFail();

        $gallery = VendorGallery::query()->where('vendor_id', $vendor->id)->findOrFail($galleryId);
        $gallery->delete();

        return response()->json(['success' => true]);
    }
}
