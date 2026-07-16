<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Resources;

use App\Core\Base\Resource;

class VendorStatisticsResource extends Resource
{
    public function toArray($request): array
    {
        return $this->resource;
    }
}
