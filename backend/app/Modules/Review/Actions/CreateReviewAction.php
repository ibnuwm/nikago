<?php

declare(strict_types=1);

namespace App\Modules\Review\Actions;

use App\Modules\Booking\Models\Booking;
use App\Modules\Review\Models\Review;
use App\Modules\Review\Models\ReviewImage;
use App\Modules\Review\Resources\ReviewResource;
use Illuminate\Contracts\Auth\Authenticatable;

class CreateReviewAction
{
    public function execute(Authenticatable $user, array $data): ReviewResource
    {
        $booking = Booking::query()
            ->forUser($user->id)
            ->where('uuid', $data['booking_uuid'])
            ->firstOrFail();

        if ($booking->status !== 'completed') {
            abort(400, 'Review can only be created for completed bookings.');
        }

        if (Review::query()->where('booking_id', $booking->id)->exists()) {
            abort(409, 'A review already exists for this booking.');
        }

        $review = Review::query()->create([
            'tenant_id' => $booking->tenant_id,
            'user_id' => $user->id,
            'booking_id' => $booking->id,
            'vendor_id' => $booking->vendor_id,
            'rating' => $data['rating'],
            'review' => $data['review'] ?? null,
        ]);

        if (! empty($data['images'])) {
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
