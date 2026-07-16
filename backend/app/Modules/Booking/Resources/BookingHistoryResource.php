<?php

declare(strict_types=1);

namespace App\Modules\Booking\Resources;

use App\Core\Base\Resource;
use Carbon\Carbon;

class BookingHistoryResource extends Resource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'status_from' => $this->resource->status_from,
            'status_to' => $this->resource->status_to,
            'notes' => $this->resource->notes,
            'changed_by' => $this->resource->changed_by,
            'created_at' => $this->resource->created_at instanceof Carbon
                ? $this->resource->created_at->toIsoString()
                : null,
        ];
    }
}
