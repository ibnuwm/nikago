<?php

declare(strict_types=1);

namespace App\Modules\Subscription\Resources;

use App\Core\Base\Resource;

class SubscriptionPlanResource extends Resource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'code' => $this->resource->code,
            'name' => $this->resource->name,
            'description' => $this->resource->description,
            'monthly_price' => (float) $this->resource->monthly_price,
            'yearly_price' => $this->resource->yearly_price !== null
                ? (float) $this->resource->yearly_price
                : null,
            'is_active' => $this->resource->is_active,
            'sort_order' => $this->resource->sort_order,
            'features' => SubscriptionFeatureResource::collection($this->whenLoaded('features')),
            'limits' => FeatureLimitResource::collection($this->whenLoaded('limits')),
        ];
    }
}
