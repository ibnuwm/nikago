<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Actions;

use App\Modules\Vendor\Models\Vendor;
use App\Modules\Vendor\Models\VendorVerification;
use App\Modules\Vendor\Resources\VendorResource;
use Illuminate\Contracts\Auth\Authenticatable;

class VerifyVendorAction
{
    public function execute(Authenticatable $user, string $uuid): VendorResource
    {
        $vendor = Vendor::query()->where('uuid', $uuid)->firstOrFail();

        $vendor->update(['verified_at' => now()]);

        VendorVerification::query()->create([
            'vendor_id' => $vendor->id,
            'verified_by' => $user->id,
            'status' => 'verified',
        ]);

        return new VendorResource($vendor->fresh());
    }
}
