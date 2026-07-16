<?php

declare(strict_types=1);

namespace App\Modules\Booking\Actions;

use App\Modules\Booking\Models\Booking;
use App\Modules\Booking\Resources\BookingCalendarResource;
use App\Modules\Vendor\Models\Vendor;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetBookingCalendarAction
{
    public function execute(array $params = []): AnonymousResourceCollection
    {
        $vendorUuid = $params['vendor_uuid'] ?? null;
        $year = (int) ($params['year'] ?? now()->year);
        $month = (int) ($params['month'] ?? now()->month);

        $query = Booking::query()
            ->where('status', '!=', 'cancelled')
            ->whereYear('event_date', $year)
            ->whereMonth('event_date', $month)
            ->with(['vendor', 'package']);

        if ($vendorUuid) {
            $vendor = Vendor::query()->where('uuid', $vendorUuid)->first();
            if ($vendor) {
                $query->where('vendor_id', $vendor->id);
            }
        }

        $bookings = $query->get();

        return BookingCalendarResource::collection($bookings);
    }
}
