<?php

declare(strict_types=1);

namespace App\Modules\Review\Actions;

use App\Modules\Review\Models\Review;
use App\Modules\Review\Resources\ReviewResource;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ListReviewsAction
{
    public function execute(Authenticatable $user, array $params = []): AnonymousResourceCollection
    {
        $perPage = (int) ($params['per_page'] ?? 15);

        $reviews = Review::query()
            ->where('user_id', $user->id)
            ->with(['images', 'vendor', 'booking'])
            ->orderByDesc('created_at')
            ->paginate($perPage);

        return ReviewResource::collection($reviews);
    }
}
