<?php

declare(strict_types=1);

namespace App\Modules\Marketplace\Actions;

use App\Modules\Marketplace\Resources\MarketplaceVendorResource;
use App\Modules\Vendor\Models\Vendor;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchMarketplaceAction
{
    public function execute(array $params = []): AnonymousResourceCollection
    {
        $perPage = min((int) ($params['per_page'] ?? 15), 100);
        $search = $params['search'] ?? null;
        $category = $params['category'] ?? null;
        $city = $params['city'] ?? null;
        $minRating = isset($params['min_rating']) ? (float) $params['min_rating'] : null;
        $verified = isset($params['verified']) ? filter_var($params['verified'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : null;
        $minPrice = isset($params['min_price']) ? (float) $params['min_price'] : null;
        $maxPrice = isset($params['max_price']) ? (float) $params['max_price'] : null;
        $sort = $params['sort'] ?? 'created_at';
        $direction = $params['direction'] ?? 'desc';

        $query = Vendor::query()
            ->active()
            ->search($search)
            ->filterByCategory($category)
            ->verified($verified)
            ->minimumRating($minRating)
            ->priceRange($minPrice, $maxPrice);

        if ($city !== null && $city !== '') {
            $query->where('city', $city);
        }

        $allowedSorts = ['created_at', 'business_name', 'rating', 'popular', 'lowest_price', 'highest_price'];
        if ($sort === 'popular') {
            $query->orderBy('total_review', $direction);
        } elseif ($sort === 'lowest_price') {
            $query->orderBy(
                Vendor::query()->selectRaw('COALESCE(MIN(price), 0)')
                    ->from('vendor_packages')
                    ->whereColumn('vendor_id', 'vendors.id'),
                'asc'
            );
        } elseif ($sort === 'highest_price') {
            $query->orderBy(
                Vendor::query()->selectRaw('COALESCE(MAX(price), 0)')
                    ->from('vendor_packages')
                    ->whereColumn('vendor_id', 'vendors.id'),
                'desc'
            );
        } else {
            $sort = in_array($sort, ['created_at', 'business_name', 'rating'], true) ? $sort : 'created_at';
            $direction = in_array($direction, ['asc', 'desc'], true) ? $direction : 'desc';
            $query->orderBy($sort, $direction);
        }

        /** @var LengthAwarePaginator $paginator */
        $paginator = $query->with('services')->paginate($perPage);

        return MarketplaceVendorResource::collection($paginator);
    }
}
