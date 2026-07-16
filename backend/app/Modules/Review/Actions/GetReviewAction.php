<?php

declare(strict_types=1);

namespace App\Modules\Review\Actions;

use App\Modules\Review\Models\Review;
use App\Modules\Review\Resources\ReviewResource;

class GetReviewAction
{
    public function execute(string $uuid): ReviewResource
    {
        $review = Review::query()
            ->where('uuid', $uuid)
            ->with(['images', 'vendor', 'booking', 'user'])
            ->firstOrFail();

        return new ReviewResource($review);
    }
}
