<?php

declare(strict_types=1);

namespace App\Modules\Review\Actions;

use App\Modules\Review\Models\Review;
use App\Modules\Review\Resources\ReviewResource;
use Illuminate\Contracts\Auth\Authenticatable;

class ReplyToReviewAction
{
    public function execute(Authenticatable $user, string $uuid, array $data): ReviewResource
    {
        $review = Review::query()
            ->where('uuid', $uuid)
            ->with(['vendor', 'images'])
            ->firstOrFail();

        if ($review->vendor->user_id !== $user->id) {
            abort(404);
        }

        $review->reply = $data['reply'];
        $review->replied_at = now();
        $review->save();

        return new ReviewResource($review);
    }
}
