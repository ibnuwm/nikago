<?php

declare(strict_types=1);

namespace App\Modules\Checklist\Resources;

use App\Core\Base\Resource;
use App\Modules\Checklist\Models\Checklist;
use Carbon\Carbon;

/**
 * @property-read Checklist $resource
 */
class ChecklistResource extends Resource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->uuid,
            'wedding_id' => $this->resource->wedding_id,
            'title' => $this->resource->title,
            'description' => $this->resource->description,
            'progress' => (float) $this->resource->progress,
            'items' => ChecklistItemResource::collection(
                $this->whenLoaded('items', $this->resource->items)
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
