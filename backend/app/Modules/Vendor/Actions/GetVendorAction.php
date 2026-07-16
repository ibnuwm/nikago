<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Actions;

use App\Modules\Vendor\Models\Vendor;
use App\Modules\Vendor\Resources\VendorResource;

class GetVendorAction
{
    public function execute(string $uuid): VendorResource
    {
        $vendor = Vendor::query()
            ->with(['services', 'packages', 'portfolios', 'galleries'])
            ->where('uuid', $uuid)
            ->firstOrFail();

        return new VendorResource($vendor);
    }
}
