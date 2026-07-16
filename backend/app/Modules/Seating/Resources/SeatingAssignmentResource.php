<?php

declare(strict_types=1);

namespace App\Modules\Seating\Resources;

use App\Core\Base\Resource;
use App\Modules\Seating\Models\SeatingAssignment;
use Carbon\Carbon;

/**
 * @property-read SeatingAssignment $resource
 */
class SeatingAssignmentResource extends Resource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->uuid,
            'table_id' => $this->resource->table_id,
            'guest_id' => $this->resource->guest_id,
            'guest_name' => $this->resource->guest?->name,
            'seat_number' => $this->resource->seat_number,
            'notes' => $this->resource->notes,
            'created_at' => $this->resource->created_at instanceof Carbon
                ? $this->resource->created_at->toIsoString()
                : null,
            'updated_at' => $this->resource->updated_at instanceof Carbon
                ? $this->resource->updated_at->toIsoString()
                : null,
        ];
    }
}
