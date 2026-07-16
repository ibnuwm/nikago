<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Actions;

use App\Modules\Vendor\Models\Vendor;
use App\Modules\Vendor\Models\VendorService;
use App\Modules\Vendor\Requests\StoreVendorRequest;
use App\Modules\Vendor\Resources\VendorResource;
use Illuminate\Contracts\Auth\Authenticatable;

class CreateVendorAction
{
    public function execute(StoreVendorRequest $request, Authenticatable $user): VendorResource
    {
        $vendor = Vendor::query()->create([
            'tenant_id' => $user->tenant_id ?? 1,
            'user_id' => $user->id,
            'business_name' => $request->input('business_name'),
            'description' => $request->input('description'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'address' => $request->input('address'),
            'city' => $request->input('city'),
            'province' => $request->input('province'),
        ]);

        $services = $request->input('services', []);
        foreach ($services as $serviceData) {
            VendorService::query()->create([
                'vendor_id' => $vendor->id,
                'name' => $serviceData['name'],
                'description' => $serviceData['description'] ?? null,
                'starting_price' => $serviceData['starting_price'] ?? null,
            ]);
        }

        return new VendorResource($vendor->load('services'));
    }
}
