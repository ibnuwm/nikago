<?php

declare(strict_types=1);

namespace App\Modules\Timeline\Resources;

use App\Core\Base\Resource;
use App\Modules\Timeline\Models\TimelineTask;
use Carbon\Carbon;

/**
 * @property-read TimelineTask $resource
 */
class TimelineTaskResource extends Resource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->uuid,
            'timeline_id' => $this->resource->timeline_id,
            'title' => $this->resource->title,
            'description' => $this->resource->description,
            'priority' => $this->resource->priority,
            'start_date' => $this->resource->start_date instanceof Carbon
                ? $this->resource->start_date->toDateString()
                : null,
            'due_date' => $this->resource->due_date instanceof Carbon
                ? $this->resource->due_date->toDateString()
                : null,
            'duration_days' => $this->resource->duration_days,
            'completed_at' => $this->resource->completed_at instanceof Carbon
                ? $this->resource->completed_at->toIsoString()
                : null,
            'sort_order' => $this->resource->sort_order,
            'created_at' => $this->resource->created_at instanceof Carbon
                ? $this->resource->created_at->toIsoString()
                : null,
            'updated_at' => $this->resource->updated_at instanceof Carbon
                ? $this->resource->updated_at->toIsoString()
                : null,
        ];
    }
}
