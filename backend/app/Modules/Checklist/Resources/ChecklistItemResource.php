<?php

declare(strict_types=1);

namespace App\Modules\Checklist\Resources;

use App\Core\Base\Resource;
use App\Modules\Checklist\Models\ChecklistItem;
use Carbon\Carbon;

/**
 * @property-read ChecklistItem $resource
 */
class ChecklistItemResource extends Resource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->uuid,
            'checklist_id' => $this->resource->checklist_id,
            'title' => $this->resource->title,
            'priority' => $this->resource->priority,
            'due_date' => $this->resource->due_date instanceof Carbon
                ? $this->resource->due_date->toDateString()
                : null,
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
