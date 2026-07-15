<?php

declare(strict_types=1);

namespace App\Modules\Guest\Resources;

use App\Core\Base\Resource;
use App\Modules\Guest\Models\Guest;
use Carbon\Carbon;

/**
 * @property-read Guest $resource
 */
class GuestResource extends Resource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->uuid,
            'wedding_id' => $this->resource->wedding_id,
            'group_id' => $this->resource->group_id,
            'category_id' => $this->resource->category_id,
            'name' => $this->resource->name,
            'phone' => $this->resource->phone,
            'email' => $this->resource->email,
            'address' => $this->resource->address,
            'pax' => $this->resource->pax,
            'qr_code' => $this->resource->qr_code,
            'invitation_sent_at' => $this->resource->invitation_sent_at instanceof Carbon
                ? $this->resource->invitation_sent_at->toIsoString()
                : null,
            'status' => $this->resource->status,
            'rsvp' => $this->whenLoaded('rsvp', fn () => [
                'status' => $this->resource->rsvp?->status,
                'total_guests' => $this->resource->rsvp?->total_guests,
            ]),
            'created_at' => $this->resource->created_at instanceof Carbon
                ? $this->resource->created_at->toIsoString()
                : null,
            'updated_at' => $this->resource->updated_at instanceof Carbon
                ? $this->resource->updated_at->toIsoString()
                : null,
        ];
    }
}
