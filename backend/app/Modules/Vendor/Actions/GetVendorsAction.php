<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Actions;

use App\Modules\Vendor\Models\Vendor;
use App\Modules\Vendor\Resources\VendorResource;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class GetVendorsAction
{
    public function execute(Authenticatable $user, array $params = []): AnonymousResourceCollection
    {
        $perPage = min((int) ($params['per_page'] ?? 15), 100);
        $search = $params['search'] ?? null;
        $category = $params['category'] ?? null;
        $verified = isset($params['verified']) ? filter_var($params['verified'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : null;
        $minRating = isset($params['min_rating']) ? (float) $params['min_rating'] : null;
        $sort = $params['sort'] ?? 'created_at';
        $direction = $params['direction'] ?? 'desc';

        $query = Vendor::query()
            ->search($search)
            ->filterByCategory($category)
            ->verified($verified)
            ->minimumRating($minRating);

        $allowedSorts = ['created_at', 'business_name', 'rating', 'city', 'province'];
        $sort = in_array($sort, $allowedSorts, true) ? $sort : 'created_at';
        $direction = in_array($direction, ['asc', 'desc'], true) ? $direction : 'desc';

        /** @var LengthAwarePaginator $paginator */
        $paginator = $query->orderBy($sort, $direction)->paginate($perPage);

        return VendorResource::collection($paginator);
    }
}
