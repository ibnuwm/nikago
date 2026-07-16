<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Actions;

use App\Modules\Vendor\Models\Vendor;
use App\Modules\Vendor\Models\VendorService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;

class DeleteVendorServiceAction
{
    public function execute(Authenticatable $user, string $vendorUuid, int $serviceId): JsonResponse
    {
        $vendor = Vendor::query()->forUser($user->id)->where('uuid', $vendorUuid)->firstOrFail();

        $service = VendorService::query()->where('vendor_id', $vendor->id)->findOrFail($serviceId);
        $service->delete();

        return response()->json(['success' => true]);
    }
}
