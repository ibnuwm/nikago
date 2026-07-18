<?php

declare(strict_types=1);

namespace App\Modules\Subscription\Actions;

use App\Modules\Subscription\Models\SubscriptionPlan;
use App\Modules\Subscription\Resources\SubscriptionPlanResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ListPlansAction
{
    public function execute(): AnonymousResourceCollection
    {
        $plans = SubscriptionPlan::query()
            ->where('is_active', true)
            ->with(['features', 'limits'])
            ->orderBy('sort_order')
            ->get();

        return SubscriptionPlanResource::collection($plans);
    }
}
