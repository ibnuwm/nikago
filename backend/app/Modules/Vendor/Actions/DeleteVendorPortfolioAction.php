<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Actions;

use App\Modules\Vendor\Models\Vendor;
use App\Modules\Vendor\Models\VendorPortfolio;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;

class DeleteVendorPortfolioAction
{
    public function execute(Authenticatable $user, string $vendorUuid, int $portfolioId): JsonResponse
    {
        $vendor = Vendor::query()->forUser($user->id)->where('uuid', $vendorUuid)->firstOrFail();

        $portfolio = VendorPortfolio::query()->where('vendor_id', $vendor->id)->findOrFail($portfolioId);
        $portfolio->delete();

        return response()->json(['success' => true]);
    }
}
