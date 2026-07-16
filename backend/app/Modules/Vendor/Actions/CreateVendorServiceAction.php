<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Actions;

use App\Modules\Vendor\Models\Vendor;
use App\Modules\Vendor\Models\VendorService;
use App\Modules\Vendor\Requests\StoreVendorServiceRequest;
use App\Modules\Vendor\Resources\VendorServiceResource;
use Illuminate\Contracts\Auth\Authenticatable;

class CreateVendorServiceAction
{
    public function execute(StoreVendorServiceRequest $request, Authenticatable $user, string $vendorUuid): VendorServiceResource
    {
        $vendor = Vendor::query()->forUser($user->id)->where('uuid', $vendorUuid)->firstOrFail();

        $service = VendorService::query()->create([
            'vendor_id' => $vendor->id,
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'starting_price' => $request->input('starting_price'),
        ]);

        return new VendorServiceResource($service);
    }
}
