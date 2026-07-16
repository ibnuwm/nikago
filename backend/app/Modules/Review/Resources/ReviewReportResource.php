<?php

declare(strict_types=1);

namespace App\Modules\Review\Resources;

use App\Core\Base\Resource;
use Carbon\Carbon;

class ReviewReportResource extends Resource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->uuid,
            'review_id' => $this->resource->review_id,
            'reason' => $this->resource->reason,
            'status' => $this->resource->status,
            'created_at' => $this->resource->created_at instanceof Carbon
                ? $this->resource->created_at->toIsoString()
                : null,
        ];
    }
}
