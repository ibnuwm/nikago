<?php

declare(strict_types=1);

namespace App\Modules\Booking\Actions;

use App\Modules\Booking\Models\Booking;
use App\Modules\Booking\Resources\BookingHistoryResource;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetBookingHistoryAction
{
    public function execute(Authenticatable $user, string $uuid): AnonymousResourceCollection
    {
        $booking = Booking::query()
            ->forUser($user->id)
            ->where('uuid', $uuid)
            ->firstOrFail();

        return BookingHistoryResource::collection(
            $booking->histories()->orderBy('created_at', 'desc')->get()
        );
    }
}
