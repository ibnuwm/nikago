<?php

declare(strict_types=1);

namespace App\Modules\Subscription\Actions;

use App\Modules\Subscription\Models\Subscription;
use App\Modules\Subscription\Models\SubscriptionPlan;
use App\Modules\Subscription\Resources\SubscriptionResource;
use App\Modules\Subscription\Services\SubscriptionService;
use Illuminate\Contracts\Auth\Authenticatable;

class DowngradeSubscriptionAction
{
    public function __construct(
        private readonly SubscriptionService $subscriptionService,
    ) {}

    public function execute(Authenticatable $user, array $data): SubscriptionResource
    {
        $tenantId = $user->tenant_id ?? 1;

        $subscription = Subscription::query()
            ->where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->with('plan')
            ->firstOrFail();

        $newPlan = SubscriptionPlan::query()
            ->where('code', $data['plan_code'])
            ->where('is_active', true)
            ->firstOrFail();

        $oldPlanId = $subscription->plan_id;

        $subscription->plan_id = $newPlan->id;
        $subscription->save();

        $this->subscriptionService->logHistory(
            $subscription,
            'downgraded',
            oldPlanId: $oldPlanId,
            notes: 'Downgrade berlaku setelah masa aktif berakhir.',
        );

        return new SubscriptionResource(
            $subscription->load(['plan.features', 'plan.limits', 'histories'])
        );
    }
}
