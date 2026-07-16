<?php

declare(strict_types=1);

namespace App\Modules\Marketplace\Actions;

use App\Modules\Marketplace\Resources\CategoryResource;
use App\Modules\Vendor\Models\VendorService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class GetCategoriesAction
{
    public function execute(): AnonymousResourceCollection
    {
        $categories = VendorService::query()
            ->select('name', DB::raw('COUNT(DISTINCT vendor_id) as vendor_count'))
            ->groupBy('name')
            ->orderBy('name')
            ->get();

        return CategoryResource::collection($categories);
    }
}
