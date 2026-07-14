<?php

declare(strict_types=1);

namespace App\Modules\RSVP\Resources;

use App\Core\Base\Resource;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RsvpResource extends Resource
{
    public function toArray(Request $request): array
    {
        $confirmedAt = $this->resource->confirmed_at;

        return [
            'id' => $this->resource->uuid,
            'guest' => [
                'id' => $this->resource->guest?->uuid,
                'name' => $this->resource->guest?->name,
                'phone' => $this->resource->guest?->phone,
                'email' => $this->resource->guest?->email,
            ],
            'attendance' => $this->resource->attendance,
            'total_guest' => $this->resource->total_guest,
            'message' => $this->resource->message,
            'confirmed_at' => $confirmedAt instanceof Carbon ? $confirmedAt->toISOString() : null,
            'created_at' => $this->resource->created_at?->toISOString(),
            'updated_at' => $this->resource->updated_at?->toISOString(),
        ];
    }
}
