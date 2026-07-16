<?php

declare(strict_types=1);

namespace App\Modules\Review\Actions;

use App\Modules\Review\Models\Review;
use App\Modules\Review\Resources\ReviewResource;
use App\Modules\Vendor\Models\Vendor;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ListVendorReviewsAction
{
    public function execute(string $vendorUuid, array $params = []): AnonymousResourceCollection
    {
        $perPage = (int) ($params['per_page'] ?? 15);

        $vendor = Vendor::query()->where('uuid', $vendorUuid)->firstOrFail();

        $reviews = Review::query()
            ->where('vendor_id', $vendor->id)
            ->where('status', 'approved')
            ->with(['images', 'user', 'vendor', 'booking'])
            ->orderByDesc('created_at')
            ->paginate($perPage);

        return ReviewResource::collection($reviews);
    }
}
