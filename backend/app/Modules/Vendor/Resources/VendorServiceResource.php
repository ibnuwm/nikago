<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Resources;

use App\Core\Base\Resource;
use App\Modules\Vendor\Models\VendorService;
use Carbon\Carbon;

/**
 * @property-read VendorService $resource
 */
class VendorServiceResource extends Resource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'description' => $this->resource->description,
            'starting_price' => $this->resource->starting_price !== null
                ? (float) $this->resource->starting_price
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
