<?php

declare(strict_types=1);

namespace App\Modules\Subscription\Resources;

use App\Core\Base\Resource;
use Carbon\Carbon;

class SubscriptionResource extends Resource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->uuid,
            'plan_id' => $this->resource->plan_id,
            'status' => $this->resource->status,
            'auto_renew' => $this->resource->auto_renew,
            'started_at' => $this->resource->started_at instanceof Carbon
                ? $this->resource->started_at->toIsoString()
                : null,
            'expired_at' => $this->resource->expired_at instanceof Carbon
                ? $this->resource->expired_at->toIsoString()
                : null,
            'trial_ends_at' => $this->resource->trial_ends_at instanceof Carbon
                ? $this->resource->trial_ends_at->toIsoString()
                : null,
            'cancelled_at' => $this->resource->cancelled_at instanceof Carbon
                ? $this->resource->cancelled_at->toIsoString()
                : null,
            'plan' => new SubscriptionPlanResource($this->whenLoaded('plan')),
            'histories' => SubscriptionHistoryResource::collection($this->whenLoaded('histories')),
        ];
    }
}
