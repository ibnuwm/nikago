<?php

declare(strict_types=1);

namespace App\Modules\Marketplace\Controllers;

use App\Core\Base\Controller;
use App\Modules\Marketplace\Actions\AddToWishlistAction;
use App\Modules\Marketplace\Actions\CompareVendorsAction;
use App\Modules\Marketplace\Actions\GetCategoriesAction;
use App\Modules\Marketplace\Actions\GetFeaturedVendorsAction;
use App\Modules\Marketplace\Actions\GetMarketplaceVendorAction;
use App\Modules\Marketplace\Actions\GetPopularVendorsAction;
use App\Modules\Marketplace\Actions\GetRecommendedVendorsAction;
use App\Modules\Marketplace\Actions\GetUserWishlistsAction;
use App\Modules\Marketplace\Actions\ListMarketplaceVendorsAction;
use App\Modules\Marketplace\Actions\RemoveFromWishlistAction;
use App\Modules\Marketplace\Actions\SearchMarketplaceAction;
use App\Modules\Marketplace\Requests\StoreCompareRequest;
use App\Modules\Marketplace\Requests\StoreWishlistRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class MarketplaceController extends Controller
{
    public function __construct(
        private readonly ListMarketplaceVendorsAction $listMarketplaceVendorsAction,
        private readonly GetMarketplaceVendorAction $getMarketplaceVendorAction,
        private readonly SearchMarketplaceAction $searchMarketplaceAction,
        private readonly GetCategoriesAction $getCategoriesAction,
        private readonly GetPopularVendorsAction $getPopularVendorsAction,
        private readonly GetRecommendedVendorsAction $getRecommendedVendorsAction,
        private readonly GetFeaturedVendorsAction $getFeaturedVendorsAction,
        private readonly AddToWishlistAction $addToWishlistAction,
        private readonly RemoveFromWishlistAction $removeFromWishlistAction,
        private readonly GetUserWishlistsAction $getUserWishlistsAction,
        private readonly CompareVendorsAction $compareVendorsAction,
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        return $this->listMarketplaceVendorsAction->execute(
            $request->only(['per_page', 'category', 'city', 'min_rating', 'verified', 'min_price', 'max_price', 'sort', 'direction'])
        );
    }

    public function show(string $uuid): JsonResponse
    {
        $userId = Auth::guard('sanctum')->check() ? Auth::guard('sanctum')->id() : null;

        return response()->json([
            'success' => true,
            'data' => $this->getMarketplaceVendorAction->execute($uuid, $userId),
        ]);
    }

    public function search(Request $request): AnonymousResourceCollection
    {
        return $this->searchMarketplaceAction->execute(
            $request->only(['search', 'per_page', 'category', 'city', 'min_rating', 'verified', 'min_price', 'max_price', 'sort', 'direction'])
        );
    }

    public function categories(): AnonymousResourceCollection
    {
        return $this->getCategoriesAction->execute();
    }

    public function popular(): AnonymousResourceCollection
    {
        return $this->getPopularVendorsAction->execute();
    }

    public function recommended(): AnonymousResourceCollection
    {
        return $this->getRecommendedVendorsAction->execute();
    }

    public function featured(): AnonymousResourceCollection
    {
        return $this->getFeaturedVendorsAction->execute();
    }

    public function wishlists(Request $request): AnonymousResourceCollection
    {
        return $this->getUserWishlistsAction->execute($request->user());
    }

    public function addWishlist(StoreWishlistRequest $request): JsonResponse
    {
        return $this->addToWishlistAction->execute(
            $request->user(),
            $request->input('vendor_uuid')
        );
    }

    public function removeWishlist(Request $request, string $wishlistUuid): JsonResponse
    {
        return $this->removeFromWishlistAction->execute($request->user(), $wishlistUuid);
    }

    public function compare(StoreCompareRequest $request): JsonResponse
    {
        return $this->compareVendorsAction->execute(
            $request->input('vendor_uuids')
        );
    }
}
