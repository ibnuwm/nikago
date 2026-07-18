<?php

declare(strict_types=1);

namespace App\Modules\Subscription\Actions;

use App\Modules\Payment\Actions\CreatePaymentAction;
use App\Modules\Subscription\Models\Subscription;
use App\Modules\Subscription\Models\SubscriptionPlan;
use App\Modules\Subscription\Resources\SubscriptionResource;
use App\Modules\Subscription\Services\SubscriptionService;
use Illuminate\Contracts\Auth\Authenticatable;

class UpgradeSubscriptionAction
{
    public function __construct(
        private readonly SubscriptionService $subscriptionService,
        private readonly CreatePaymentAction $createPaymentAction,
    ) {}

    public function execute(Authenticatable $user, array $data): SubscriptionResource
    {
        $tenantId = $user->tenant_id ?? 1;

        $subscription = Subscription::query()
            ->where('tenant_id', $tenantId)
            ->whereIn('status', ['active', 'trialing'])
            ->with('plan')
            ->firstOrFail();

        $newPlan = SubscriptionPlan::query()
            ->where('code', $data['plan_code'])
            ->where('is_active', true)
            ->firstOrFail();

        $oldPlanId = $subscription->plan_id;

        $billingPeriod = $data['billing_period'] ?? 'monthly';
        $price = $this->subscriptionService->getPlanPrice($newPlan, $billingPeriod);
        $expiryDate = $this->subscriptionService->getExpiryDate($billingPeriod);

        $subscription->plan_id = $newPlan->id;
        $subscription->expired_at = $expiryDate;
        $subscription->save();

        $this->subscriptionService->logHistory(
            $subscription,
            'upgraded',
            oldPlanId: $oldPlanId,
        );

        if ($price > 0) {
            $this->createPaymentAction->execute($user, [
                'items' => [
                    [
                        'item_type' => 'subscription',
                        'item_id' => $subscription->id,
                        'name' => "Upgrade ke {$newPlan->name} ({$billingPeriod})",
                        'amount' => $price,
                        'quantity' => 1,
                    ],
                ],
            ]);
        }

        return new SubscriptionResource(
            $subscription->load(['plan.features', 'plan.limits', 'histories'])
        );
    }
}
