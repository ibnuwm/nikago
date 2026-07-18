<?php

declare(strict_types=1);

namespace App\Modules\Subscription\Actions;

use App\Modules\Subscription\Models\Subscription;
use App\Modules\Subscription\Resources\SubscriptionFeatureResource;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetFeaturesAction
{
    public function execute(Authenticatable $user): AnonymousResourceCollection
    {
        $tenantId = $user->tenant_id ?? 1;

        $subscription = Subscription::query()
            ->where('tenant_id', $tenantId)
            ->whereIn('status', ['active', 'trialing'])
            ->with('plan.features')
            ->first();

        if (!$subscription || !$subscription->plan) {
            return SubscriptionFeatureResource::collection(collect());
        }

        return SubscriptionFeatureResource::collection(
            $subscription->plan->features
        );
    }
}
