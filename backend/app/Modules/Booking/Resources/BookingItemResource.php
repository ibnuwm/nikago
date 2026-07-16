<?php

declare(strict_types=1);

namespace App\Modules\Booking\Resources;

use App\Core\Base\Resource;

class BookingItemResource extends Resource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'price' => (float) $this->resource->price,
            'quantity' => $this->resource->quantity,
        ];
    }
}
