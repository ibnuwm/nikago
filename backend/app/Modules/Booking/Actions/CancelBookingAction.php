<?php

declare(strict_types=1);

namespace App\Modules\Booking\Actions;

use App\Modules\Booking\Models\Booking;
use App\Modules\Booking\Models\BookingHistory;
use App\Modules\Booking\Resources\BookingResource;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;

class CancelBookingAction
{
    public function execute(Authenticatable $user, string $uuid): JsonResponse
    {
        $booking = Booking::query()
            ->forUser($user->id)
            ->where('uuid', $uuid)
            ->firstOrFail();

        if ($booking->status === 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'Booking is already cancelled',
            ], 422);
        }

        $oldStatus = $booking->status;
        $booking->update(['status' => 'cancelled']);

        BookingHistory::query()->create([
            'booking_id' => $booking->id,
            'status_from' => $oldStatus,
            'status_to' => 'cancelled',
            'changed_by' => $user->id,
        ]);

        return response()->json([
            'success' => true,
            'data' => new BookingResource(
                $booking->fresh()->load(['vendor', 'package', 'histories'])
            ),
        ]);
    }
}
