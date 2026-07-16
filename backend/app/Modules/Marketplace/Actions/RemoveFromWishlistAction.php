<?php

declare(strict_types=1);

namespace App\Modules\Marketplace\Actions;

use App\Modules\Marketplace\Models\Wishlist;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;

class RemoveFromWishlistAction
{
    public function execute(Authenticatable $user, string $wishlistUuid): JsonResponse
    {
        $wishlist = Wishlist::query()
            ->where('uuid', $wishlistUuid)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $wishlist->delete();

        return response()->json([
            'success' => true,
            'message' => 'Removed from wishlist',
        ]);
    }
}
