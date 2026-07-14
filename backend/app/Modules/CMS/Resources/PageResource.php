<?php

declare(strict_types=1);

namespace App\Modules\CMS\Resources;

use App\Core\Base\Resource;
use App\Modules\CMS\Models\Page;
use Illuminate\Http\Request;

/**
 * @property-read Page $resource
 */
class PageResource extends Resource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->uuid,
            'title' => $this->resource->title,
            'slug' => $this->resource->slug,
            'content' => $this->resource->content,
            'meta_title' => $this->resource->meta_title,
            'meta_description' => $this->resource->meta_description,
            'status' => $this->resource->status,
            'created_at' => $this->resource->created_at?->toISOString(),
            'updated_at' => $this->resource->updated_at?->toISOString(),
        ];
    }
}
