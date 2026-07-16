<?php

declare(strict_types=1);

namespace App\Modules\Review\Resources;

use App\Core\Base\Resource;
use Carbon\Carbon;

class ReviewResource extends Resource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->uuid,
            'user_id' => $this->resource->user_id,
            'user_name' => $this->resource->user?->name,
            'vendor_id' => $this->resource->vendor_id,
            'vendor_uuid' => $this->resource->vendor?->uuid,
            'vendor_name' => $this->resource->vendor?->business_name,
            'booking_id' => $this->resource->booking_id,
            'booking_uuid' => $this->resource->booking?->uuid,
            'rating' => $this->resource->rating,
            'review' => $this->resource->review,
            'reply' => $this->resource->reply,
            'replied_at' => $this->resource->replied_at instanceof Carbon
                ? $this->resource->replied_at->toIsoString()
                : null,
            'status' => $this->resource->status,
            'images' => ReviewImageResource::collection($this->whenLoaded('images')),
            'created_at' => $this->resource->created_at instanceof Carbon
                ? $this->resource->created_at->toIsoString()
                : null,
            'updated_at' => $this->resource->updated_at instanceof Carbon
                ? $this->resource->updated_at->toIsoString()
                : null,
        ];
    }
}
