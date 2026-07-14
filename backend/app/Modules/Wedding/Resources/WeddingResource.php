<?php

declare(strict_types=1);

namespace App\Modules\Wedding\Resources;

use App\Core\Base\Resource;
use App\Modules\Wedding\Models\Wedding;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * @property-read Wedding $resource
 */
class WeddingResource extends Resource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->uuid,
            'title' => $this->resource->title,
            'slug' => $this->resource->slug,
            'status' => $this->resource->status,
            'theme' => $this->resource->theme,
            'cover_image' => $this->resource->cover_image,
            'published_at' => $this->resource->published_at instanceof Carbon
                ? $this->resource->published_at->toIsoString()
                : null,
            'created_at' => $this->resource->created_at instanceof Carbon
                ? $this->resource->created_at->toIsoString()
                : null,
            'updated_at' => $this->resource->updated_at instanceof Carbon
                ? $this->resource->updated_at->toIsoString()
                : null,
        ];
    }
}
