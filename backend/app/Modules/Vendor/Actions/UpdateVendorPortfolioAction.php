<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Actions;

use App\Modules\Vendor\Models\Vendor;
use App\Modules\Vendor\Models\VendorPortfolio;
use App\Modules\Vendor\Requests\UpdateVendorPortfolioRequest;
use App\Modules\Vendor\Resources\VendorPortfolioResource;
use Illuminate\Contracts\Auth\Authenticatable;

class UpdateVendorPortfolioAction
{
    public function execute(UpdateVendorPortfolioRequest $request, Authenticatable $user, string $vendorUuid, int $portfolioId): VendorPortfolioResource
    {
        Vendor::query()->forUser($user->id)->where('uuid', $vendorUuid)->firstOrFail();

        $portfolio = VendorPortfolio::query()->findOrFail($portfolioId);
        $portfolio->update($request->validated());

        return new VendorPortfolioResource($portfolio->fresh());
    }
}
