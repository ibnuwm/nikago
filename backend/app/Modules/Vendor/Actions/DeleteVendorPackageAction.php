<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Actions;

use App\Modules\Vendor\Models\Vendor;
use App\Modules\Vendor\Models\VendorPackage;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;

class DeleteVendorPackageAction
{
    public function execute(Authenticatable $user, string $vendorUuid, int $packageId): JsonResponse
    {
        $vendor = Vendor::query()->forUser($user->id)->where('uuid', $vendorUuid)->firstOrFail();

        $package = VendorPackage::query()->where('vendor_id', $vendor->id)->findOrFail($packageId);
        $package->delete();

        return response()->json(['success' => true]);
    }
}
