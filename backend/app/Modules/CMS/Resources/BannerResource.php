<?php

declare(strict_types=1);

namespace App\Modules\CMS\Resources;

use App\Core\Base\Resource;
use App\Modules\CMS\Models\Banner;
use Illuminate\Http\Request;

/**
 * @property-read Banner $resource
 */
class BannerResource extends Resource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->uuid,
            'title' => $this->resource->title,
            'subtitle' => $this->resource->subtitle,
            'image' => $this->resource->image,
            'link' => $this->resource->link,
            'sort_order' => $this->resource->sort_order,
            'created_at' => $this->resource->created_at?->toISOString(),
            'updated_at' => $this->resource->updated_at?->toISOString(),
        ];
    }
}
