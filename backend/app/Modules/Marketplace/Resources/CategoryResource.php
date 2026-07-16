<?php

declare(strict_types=1);

namespace App\Modules\Marketplace\Resources;

use App\Core\Base\Resource;

/**
 * @property-read object{name: string, vendor_count: int} $resource
 */
class CategoryResource extends Resource
{
    public function toArray($request): array
    {
        return [
            'name' => $this->resource->name,
            'vendor_count' => (int) $this->resource->vendor_count,
        ];
    }
}
