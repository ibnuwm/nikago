<?php

declare(strict_types=1);

namespace App\Modules\Subscription\Resources;

use App\Core\Base\Resource;

class SubscriptionFeatureResource extends Resource
{
    public function toArray($request): array
    {
        return [
            'code' => $this->resource->code,
            'name' => $this->resource->name,
            'description' => $this->resource->description,
        ];
    }
}
