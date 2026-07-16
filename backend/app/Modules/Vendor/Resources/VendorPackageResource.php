<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Resources;

use App\Core\Base\Resource;
use App\Modules\Vendor\Models\VendorPackage;
use Carbon\Carbon;

/**
 * @property-read VendorPackage $resource
 */
class VendorPackageResource extends Resource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'description' => $this->resource->description,
            'price' => (float) $this->resource->price,
            'inclusions' => $this->resource->inclusions,
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
