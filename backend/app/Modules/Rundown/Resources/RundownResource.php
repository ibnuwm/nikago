<?php

declare(strict_types=1);

namespace App\Modules\Rundown\Resources;

use App\Core\Base\Resource;
use App\Modules\Rundown\Models\Rundown;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * @property-read Rundown $resource
 */
class RundownResource extends Resource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->uuid,
            'wedding_id' => $this->resource->wedding_id,
            'title' => $this->resource->title,
            'description' => $this->resource->description,
            'status' => $this->resource->status,
            'published_at' => $this->resource->published_at instanceof Carbon
                ? $this->resource->published_at->toIsoString()
                : null,
            'items' => RundownItemResource::collection(
                $this->whenLoaded('items', $this->resource->items)
            ),
            'created_at' => $this->resource->created_at instanceof Carbon
                ? $this->resource->created_at->toIsoString()
                : null,
            'updated_at' => $this->resource->updated_at instanceof Carbon
                ? $this->resource->updated_at->toIsoString()
                : null,
        ];
    }
}
