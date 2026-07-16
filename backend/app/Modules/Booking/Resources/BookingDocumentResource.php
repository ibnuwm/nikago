<?php

declare(strict_types=1);

namespace App\Modules\Booking\Resources;

use App\Core\Base\Resource;
use Carbon\Carbon;

class BookingDocumentResource extends Resource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'type' => $this->resource->type,
            'file_url' => $this->resource->file_url,
            'notes' => $this->resource->notes,
            'created_at' => $this->resource->created_at instanceof Carbon
                ? $this->resource->created_at->toIsoString()
                : null,
        ];
    }
}
