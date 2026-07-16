<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Actions;

use App\Modules\Vendor\Models\Vendor;
use App\Modules\Vendor\Resources\VendorResource;

class ActivateVendorAction
{
    public function execute(string $uuid): VendorResource
    {
        $vendor = Vendor::query()->where('uuid', $uuid)->firstOrFail();

        $vendor->update(['status' => 'active']);

        return new VendorResource($vendor->fresh());
    }
}
