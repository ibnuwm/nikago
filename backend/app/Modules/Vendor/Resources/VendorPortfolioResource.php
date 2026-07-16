<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Resources;

use App\Core\Base\Resource;
use App\Modules\Vendor\Models\VendorPortfolio;
use Carbon\Carbon;

/**
 * @property-read VendorPortfolio $resource
 */
class VendorPortfolioResource extends Resource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'description' => $this->resource->description,
            'image_url' => $this->resource->image_url,
            'created_at' => $this->resource->created_at instanceof Carbon
                ? $this->resource->created_at->toIsoString()
                : null,
            'updated_at' => $this->resource->updated_at instanceof Carbon
                ? $this->resource->updated_at->toIsoString()
                : null,
        ];
    }
}
