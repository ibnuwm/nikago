<?php

declare(strict_types=1);

namespace App\Modules\Subscription\Actions;

use App\Modules\Subscription\Models\Subscription;
use App\Modules\Subscription\Resources\SubscriptionHistoryResource;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetSubscriptionHistoryAction
{
    public function execute(Authenticatable $user): AnonymousResourceCollection
    {
        $tenantId = $user->tenant_id ?? 1;

        $subscription = Subscription::query()
            ->where('tenant_id', $tenantId)
            ->whereIn('status', ['active', 'trialing'])
            ->first();

        if (!$subscription) {
            return SubscriptionHistoryResource::collection(collect());
        }

        $histories = $subscription->histories()
            ->with(['plan', 'oldPlan'])
            ->orderByDesc('created_at')
            ->get();

        return SubscriptionHistoryResource::collection($histories);
    }
}
