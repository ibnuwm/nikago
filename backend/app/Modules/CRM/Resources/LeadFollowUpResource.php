<?php

declare(strict_types=1);

namespace App\Modules\CRM\Resources;

use App\Core\Base\Resource;
use Carbon\Carbon;

class LeadFollowUpResource extends Resource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->uuid,
            'type' => $this->resource->type,
            'notes' => $this->resource->notes,
            'follow_up_date' => $this->resource->follow_up_date instanceof Carbon
                ? $this->resource->follow_up_date->toIsoString()
                : null,
            'is_completed' => $this->resource->is_completed,
            'completed_at' => $this->resource->completed_at instanceof Carbon
                ? $this->resource->completed_at->toIsoString()
                : null,
            'created_at' => $this->resource->created_at instanceof Carbon
                ? $this->resource->created_at->toIsoString()
                : null,
        ];
    }
}
