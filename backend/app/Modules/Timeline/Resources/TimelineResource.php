<?php

declare(strict_types=1);

namespace App\Modules\Timeline\Resources;

use App\Core\Base\Resource;
use App\Modules\Timeline\Models\Timeline;
use Carbon\Carbon;

/**
 * @property-read Timeline $resource
 */
class TimelineResource extends Resource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->uuid,
            'wedding_id' => $this->resource->wedding_id,
            'title' => $this->resource->title,
            'description' => $this->resource->description,
            'progress' => (float) $this->resource->progress,
            'completed_at' => $this->resource->completed_at instanceof Carbon
                ? $this->resource->completed_at->toIsoString()
                : null,
            'tasks' => TimelineTaskResource::collection(
                $this->whenLoaded('tasks', $this->resource->tasks)
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
