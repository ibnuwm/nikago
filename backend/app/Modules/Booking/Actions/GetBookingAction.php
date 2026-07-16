<?php

declare(strict_types=1);

namespace App\Modules\Booking\Actions;

use App\Modules\Booking\Models\Booking;
use App\Modules\Booking\Resources\BookingResource;
use Illuminate\Contracts\Auth\Authenticatable;

class GetBookingAction
{
    public function execute(Authenticatable $user, string $uuid): BookingResource
    {
        $booking = Booking::query()
            ->forUser($user->id)
            ->where('uuid', $uuid)
            ->with(['vendor', 'package', 'items', 'histories', 'documents'])
            ->firstOrFail();

        return new BookingResource($booking);
    }
}
