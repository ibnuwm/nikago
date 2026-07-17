<?php

declare(strict_types=1);

namespace App\Modules\CMS\Resources;

use App\Core\Base\Resource;
use App\Modules\CMS\Models\BlogTag;
use Illuminate\Http\Request;

/**
 * @property-read BlogTag $resource
 */
class BlogTagResource extends Resource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->uuid,
            'name' => $this->resource->name,
            'slug' => $this->resource->slug,
            'post_count' => $this->whenCounted('posts'),
            'created_at' => $this->resource->created_at?->toISOString(),
            'updated_at' => $this->resource->updated_at?->toISOString(),
        ];
    }
}
