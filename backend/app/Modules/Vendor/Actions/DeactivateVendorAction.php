<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Actions;

use App\Modules\Vendor\Models\Vendor;
use App\Modules\Vendor\Resources\VendorResource;

class DeactivateVendorAction
{
    public function execute(string $uuid): VendorResource
    {
        $vendor = Vendor::query()->where('uuid', $uuid)->firstOrFail();

        $vendor->update(['status' => 'inactive']);

        return new VendorResource($vendor->fresh());
    }
}
