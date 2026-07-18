<?php

declare(strict_types=1);

namespace App\Modules\Subscription\Resources;

use App\Core\Base\Resource;
use Carbon\Carbon;

class SubscriptionHistoryResource extends Resource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'action' => $this->resource->action,
            'notes' => $this->resource->notes,
            'plan' => $this->whenLoaded('plan', fn () => [
                'code' => $this->resource->plan->code,
                'name' => $this->resource->plan->name,
            ]),
            'old_plan' => $this->whenLoaded('oldPlan', fn () => [
                'code' => $this->resource->oldPlan->code,
                'name' => $this->resource->oldPlan->name,
            ]),
            'created_at' => $this->resource->created_at instanceof Carbon
                ? $this->resource->created_at->toIsoString()
                : null,
        ];
    }
}
