<?php

declare(strict_types=1);

namespace App\Modules\Review\Actions;

use App\Modules\Review\Models\Review;
use App\Modules\Review\Resources\ReviewResource;
use App\Modules\Vendor\Models\Vendor;
use Illuminate\Contracts\Auth\Authenticatable;

class ReplyToReviewAction
{
    public function execute(Authenticatable $user, string $uuid, array $data): ReviewResource
    {
        $review = Review::query()
            ->where('uuid', $uuid)
            ->with(['vendor', 'images'])
            ->firstOrFail();

        $vendor = Vendor::query()
            ->where('id', $review->vendor_id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $review->reply = $data['reply'];
        $review->replied_at = now();
        $review->save();

        return new ReviewResource($review);
    }
}
