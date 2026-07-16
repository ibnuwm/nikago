<?php

declare(strict_types=1);

namespace App\Modules\Booking\Actions;

use App\Modules\Booking\Models\Booking;
use App\Modules\Booking\Resources\BookingResource;
use Illuminate\Contracts\Auth\Authenticatable;

class UpdateBookingAction
{
    public function execute(Authenticatable $user, string $uuid, array $data): BookingResource
    {
        $booking = Booking::query()
            ->forUser($user->id)
            ->where('uuid', $uuid)
            ->firstOrFail();

        $booking->update($data);

        return new BookingResource(
            $booking->fresh()->load(['vendor', 'package', 'histories'])
        );
    }
}
