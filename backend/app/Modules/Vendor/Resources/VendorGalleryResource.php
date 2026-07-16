<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Resources;

use App\Core\Base\Resource;
use App\Modules\Vendor\Models\VendorGallery;
use Carbon\Carbon;

/**
 * @property-read VendorGallery $resource
 */
class VendorGalleryResource extends Resource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'image_url' => $this->resource->image_url,
            'caption' => $this->resource->caption,
            'sort_order' => $this->resource->sort_order,
            'created_at' => $this->resource->created_at instanceof Carbon
                ? $this->resource->created_at->toIsoString()
                : null,
            'updated_at' => $this->resource->updated_at instanceof Carbon
                ? $this->resource->updated_at->toIsoString()
                : null,
        ];
    }
}
