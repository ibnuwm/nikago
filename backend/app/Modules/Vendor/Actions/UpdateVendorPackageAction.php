<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Actions;

use App\Modules\Vendor\Models\Vendor;
use App\Modules\Vendor\Models\VendorPackage;
use App\Modules\Vendor\Requests\UpdateVendorPackageRequest;
use App\Modules\Vendor\Resources\VendorPackageResource;
use Illuminate\Contracts\Auth\Authenticatable;

class UpdateVendorPackageAction
{
    public function execute(UpdateVendorPackageRequest $request, Authenticatable $user, string $vendorUuid, int $packageId): VendorPackageResource
    {
        Vendor::query()->forUser($user->id)->where('uuid', $vendorUuid)->firstOrFail();

        $package = VendorPackage::query()->findOrFail($packageId);
        $package->update($request->validated());

        return new VendorPackageResource($package->fresh());
    }
}
