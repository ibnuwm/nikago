<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Actions;

use App\Modules\Vendor\Models\Vendor;
use App\Modules\Vendor\Models\VendorPackage;
use App\Modules\Vendor\Requests\StoreVendorPackageRequest;
use App\Modules\Vendor\Resources\VendorPackageResource;
use Illuminate\Contracts\Auth\Authenticatable;

class CreateVendorPackageAction
{
    public function execute(StoreVendorPackageRequest $request, Authenticatable $user, string $vendorUuid): VendorPackageResource
    {
        $vendor = Vendor::query()->forUser($user->id)->where('uuid', $vendorUuid)->firstOrFail();

        $maxOrder = (int) VendorPackage::query()->where('vendor_id', $vendor->id)->max('sort_order');

        $package = VendorPackage::query()->create([
            'vendor_id' => $vendor->id,
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'inclusions' => $request->input('inclusions'),
            'sort_order' => $request->input('sort_order', $maxOrder + 1),
        ]);

        return new VendorPackageResource($package);
    }
}
