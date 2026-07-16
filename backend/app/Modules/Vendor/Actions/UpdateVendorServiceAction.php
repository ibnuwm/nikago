<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Actions;

use App\Modules\Vendor\Models\Vendor;
use App\Modules\Vendor\Models\VendorService;
use App\Modules\Vendor\Requests\UpdateVendorServiceRequest;
use App\Modules\Vendor\Resources\VendorServiceResource;
use Illuminate\Contracts\Auth\Authenticatable;

class UpdateVendorServiceAction
{
    public function execute(UpdateVendorServiceRequest $request, Authenticatable $user, string $vendorUuid, int $serviceId): VendorServiceResource
    {
        Vendor::query()->forUser($user->id)->where('uuid', $vendorUuid)->firstOrFail();

        $service = VendorService::query()->findOrFail($serviceId);
        $service->update($request->validated());

        return new VendorServiceResource($service->fresh());
    }
}
