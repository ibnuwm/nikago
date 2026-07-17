<?php

declare(strict_types=1);

namespace App\Modules\CMS\Resources;

use App\Core\Base\Resource;
use App\Modules\CMS\Models\BlogCategory;
use Illuminate\Http\Request;

/**
 * @property-read BlogCategory $resource
 */
class BlogCategoryResource extends Resource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->uuid,
            'name' => $this->resource->name,
            'slug' => $this->resource->slug,
            'description' => $this->resource->description,
            'post_count' => $this->whenCounted('posts'),
            'created_at' => $this->resource->created_at?->toISOString(),
            'updated_at' => $this->resource->updated_at?->toISOString(),
        ];
    }
}
