<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Actions;

use App\Modules\Vendor\Models\Vendor;
use App\Modules\Vendor\Models\VendorService;
use App\Modules\Vendor\Requests\UpdateVendorRequest;
use App\Modules\Vendor\Resources\VendorResource;
use Illuminate\Contracts\Auth\Authenticatable;

class UpdateVendorAction
{
    public function execute(UpdateVendorRequest $request, Authenticatable $user, string $uuid): VendorResource
    {
        $vendor = Vendor::query()->forUser($user->id)->where('uuid', $uuid)->firstOrFail();

        $vendor->update($request->validated());

        if ($request->has('services')) {
            $vendor->services()->delete();

            foreach ($request->input('services', []) as $serviceData) {
                VendorService::query()->create([
                    'vendor_id' => $vendor->id,
                    'name' => $serviceData['name'],
                    'description' => $serviceData['description'] ?? null,
                    'starting_price' => $serviceData['starting_price'] ?? null,
                ]);
            }
        }

        return new VendorResource($vendor->fresh()->load('services'));
    }
}
