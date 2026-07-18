<?php

declare(strict_types=1);

namespace App\Modules\Subscription\Resources;

use App\Core\Base\Resource;

class FeatureLimitResource extends Resource
{
    public function toArray($request): array
    {
        return [
            'feature_code' => $this->resource->feature_code,
            'limit_value' => $this->resource->limit_value,
        ];
    }
}
