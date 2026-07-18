<?php

declare(strict_types=1);

namespace App\Modules\Payment\Resources;

use App\Core\Base\Resource;
use Carbon\Carbon;

class RefundResource extends Resource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->uuid,
            'amount' => (float) $this->resource->amount,
            'reason' => $this->resource->reason,
            'status' => $this->resource->status,
            'created_at' => $this->resource->created_at instanceof Carbon
                ? $this->resource->created_at->toIsoString()
                : null,
        ];
    }
}
