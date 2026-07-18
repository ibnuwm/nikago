<?php

declare(strict_types=1);

namespace App\Modules\CRM\Resources;

use App\Core\Base\Resource;
use Carbon\Carbon;

class LeadResource extends Resource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->uuid,
            'name' => $this->resource->name,
            'email' => $this->resource->email,
            'phone' => $this->resource->phone,
            'source' => $this->resource->source,
            'stage' => $this->resource->stage,
            'deal_value' => $this->resource->deal_value !== null
                ? (float) $this->resource->deal_value
                : null,
            'notes' => $this->resource->notes,
            'assigned_to' => $this->whenLoaded('assignedTo', fn () => [
                'id' => (string) $this->resource->assignedTo->id,
                'name' => $this->resource->assignedTo->name,
            ]),
            'follow_ups' => LeadFollowUpResource::collection($this->whenLoaded('followUps')),
            'activities' => LeadActivityResource::collection($this->whenLoaded('activities')),
            'closed_at' => $this->resource->closed_at instanceof Carbon
                ? $this->resource->closed_at->toIsoString()
                : null,
            'created_at' => $this->resource->created_at instanceof Carbon
                ? $this->resource->created_at->toIsoString()
                : null,
            'updated_at' => $this->resource->updated_at instanceof Carbon
                ? $this->resource->updated_at->toIsoString()
                : null,
        ];
    }
}
