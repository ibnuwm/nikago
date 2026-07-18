<?php

declare(strict_types=1);

namespace App\Modules\Subscription\Actions;

use App\Modules\Subscription\Models\Subscription;
use App\Modules\Subscription\Models\SubscriptionPlan;
use App\Modules\Subscription\Resources\SubscriptionResource;
use Illuminate\Contracts\Auth\Authenticatable;

class GetCurrentSubscriptionAction
{
    public function execute(Authenticatable $user): SubscriptionResource
    {
        $tenantId = $user->tenant_id ?? 1;

        $subscription = Subscription::query()
            ->where('tenant_id', $tenantId)
            ->whereIn('status', ['active', 'trialing'])
            ->with(['plan.features', 'plan.limits', 'histories'])
            ->latest('started_at')
            ->first();

        if (!$subscription) {
            $freePlan = $this->ensureFreePlan();

            $subscription = Subscription::query()->create([
                'tenant_id' => $tenantId,
                'plan_id' => $freePlan->id,
                'started_at' => now(),
                'expired_at' => now()->addYear(100),
                'status' => 'active',
            ]);

            $subscription->load(['plan.features', 'plan.limits', 'histories']);
        }

        return new SubscriptionResource($subscription);
    }

    private function ensureFreePlan(): SubscriptionPlan
    {
        $plan = SubscriptionPlan::query()->where('code', 'FREE')->first();

        if (!$plan) {
            $plan = SubscriptionPlan::query()->create([
                'code' => 'FREE',
                'name' => 'Free',
                'description' => 'Paket gratis untuk memulai.',
                'monthly_price' => 0,
                'is_active' => true,
                'sort_order' => 0,
            ]);
        }

        return $plan;
    }
}
