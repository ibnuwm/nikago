<?php

declare(strict_types=1);

namespace App\Modules\Seating\Resources;

use App\Core\Base\Resource;
use App\Modules\Seating\Models\SeatingTable;
use Carbon\Carbon;

/**
 * @property-read SeatingTable $resource
 */
class SeatingTableResource extends Resource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->uuid,
            'wedding_id' => $this->resource->wedding_id,
            'name' => $this->resource->name,
            'capacity' => $this->resource->capacity,
            'shape' => $this->resource->shape,
            'position_x' => $this->resource->position_x,
            'position_y' => $this->resource->position_y,
            'sort_order' => $this->resource->sort_order,
            'assigned_count' => $this->resource->relationLoaded('assignments')
                ? $this->resource->assignments->count()
                : 0,
            'guests' => SeatingAssignmentResource::collection(
                $this->whenLoaded('assignments')
            ),
            'created_at' => $this->resource->created_at instanceof Carbon
                ? $this->resource->created_at->toIsoString()
                : null,
            'updated_at' => $this->resource->updated_at instanceof Carbon
                ? $this->resource->updated_at->toIsoString()
                : null,
        ];
    }
}
