<?php

declare(strict_types=1);

namespace App\Modules\Booking\Resources;

use App\Core\Base\Resource;
use App\Modules\Booking\Models\Booking;
use Carbon\Carbon;

/**
 * @property-read Booking $resource
 */
class BookingCalendarResource extends Resource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->uuid,
            'vendor_name' => $this->resource->vendor->business_name ?? null,
            'package_name' => $this->resource->package->name ?? null,
            'event_date' => $this->resource->event_date instanceof Carbon
                ? $this->resource->event_date->toDateString()
                : $this->resource->event_date,
            'status' => $this->resource->status,
        ];
    }
}
