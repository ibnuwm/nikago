<?php

declare(strict_types=1);

namespace App\Modules\Marketplace\Actions;

use App\Modules\Marketplace\Resources\MarketplaceVendorResource;
use App\Modules\Vendor\Models\Vendor;
use Illuminate\Http\JsonResponse;

class CompareVendorsAction
{
    public function execute(array $vendorUuids): JsonResponse
    {
        $vendors = Vendor::query()
            ->active()
            ->whereIn('uuid', $vendorUuids)
            ->with(['services', 'packages', 'portfolios', 'galleries'])
            ->get();

        if ($vendors->count() < 2) {
            return response()->json([
                'success' => false,
                'message' => 'At least 2 vendors are required for comparison',
            ], 422);
        }

        $resource = MarketplaceVendorResource::collection($vendors);

        return response()->json([
            'success' => true,
            'data' => $resource,
        ]);
    }
}
