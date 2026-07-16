<?php

declare(strict_types=1);

namespace App\Modules\Review\Actions;

use App\Modules\Review\Models\Review;
use App\Modules\Review\Models\ReviewImage;
use App\Modules\Review\Resources\ReviewResource;
use Illuminate\Contracts\Auth\Authenticatable;

class UpdateReviewAction
{
    public function execute(Authenticatable $user, string $uuid, array $data): ReviewResource
    {
        $review = Review::query()
            ->where('uuid', $uuid)
            ->where('user_id', $user->id)
            ->firstOrFail();

        if (isset($data['rating'])) {
            $review->rating = $data['rating'];
        }

        if (array_key_exists('review', $data)) {
            $review->review = $data['review'];
        }

        $review->save();

        if (isset($data['images'])) {
            $review->images()->delete();

            foreach ($data['images'] as $sortOrder => $imageUrl) {
                ReviewImage::query()->create([
                    'review_id' => $review->id,
                    'image_url' => $imageUrl,
                    'sort_order' => $sortOrder,
                ]);
            }
        }

        $review->load(['images', 'vendor', 'booking']);

        return new ReviewResource($review);
    }
}
