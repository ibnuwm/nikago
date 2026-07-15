<?php

declare(strict_types=1);

namespace App\Modules\Guest\Resources;

use App\Core\Base\Resource;
use App\Modules\Guest\Models\Guest;
use Carbon\Carbon;

/**
 * @property-read Guest $resource
 */
class GuestCheckInResource extends Resource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->uuid,
            'name' => $this->resource->name,
            'phone' => $this->resource->phone,
            'pax' => $this->resource->pax,
            'status' => $this->resource->status,
            'checked_in_at' => $this->resource->invitation_sent_at instanceof Carbon
                ? $this->resource->invitation_sent_at->toIsoString()
                : null,
        ];
    }
}
