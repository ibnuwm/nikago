<?php

declare(strict_types=1);

namespace App\Modules\CRM\Resources;

use App\Core\Base\Resource;
use Carbon\Carbon;

class LeadActivityResource extends Resource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->uuid,
            'type' => $this->resource->type,
            'description' => $this->resource->description,
            'metadata' => $this->resource->metadata,
            'created_at' => $this->resource->created_at instanceof Carbon
                ? $this->resource->created_at->toIsoString()
                : null,
        ];
    }
}
