<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Actions;

use App\Modules\Vendor\Models\Vendor;
use App\Modules\Vendor\Models\VendorPortfolio;
use App\Modules\Vendor\Requests\StoreVendorPortfolioRequest;
use App\Modules\Vendor\Resources\VendorPortfolioResource;
use Illuminate\Contracts\Auth\Authenticatable;

class CreateVendorPortfolioAction
{
    public function execute(StoreVendorPortfolioRequest $request, Authenticatable $user, string $vendorUuid): VendorPortfolioResource
    {
        $vendor = Vendor::query()->forUser($user->id)->where('uuid', $vendorUuid)->firstOrFail();

        $portfolio = VendorPortfolio::query()->create([
            'vendor_id' => $vendor->id,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'image_url' => $request->input('image_url'),
        ]);

        return new VendorPortfolioResource($portfolio);
    }
}
