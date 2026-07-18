<?php

declare(strict_types=1);

namespace App\Modules\Subscription\Actions;

use App\Modules\Payment\Actions\CreatePaymentAction;
use App\Modules\Subscription\Models\Subscription;
use App\Modules\Subscription\Models\SubscriptionPlan;
use App\Modules\Subscription\Resources\SubscriptionResource;
use App\Modules\Subscription\Services\SubscriptionService;
use Illuminate\Contracts\Auth\Authenticatable;

class SubscribeAction
{
    public function __construct(
        private readonly SubscriptionService $subscriptionService,
        private readonly CreatePaymentAction $createPaymentAction,
    ) {}

    public function execute(Authenticatable $user, array $data): SubscriptionResource
    {
        $plan = SubscriptionPlan::query()
            ->where('code', $data['plan_code'])
            ->where('is_active', true)
            ->firstOrFail();

        $tenantId = $user->tenant_id ?? 1;

        $existingSubscription = Subscription::query()
            ->where('tenant_id', $tenantId)
            ->whereIn('status', ['active', 'trialing'])
            ->first();

        if ($existingSubscription) {
            $existingSubscription->status = 'cancelled';
            $existingSubscription->cancelled_at = now();
            $existingSubscription->save();

            $this->subscriptionService->logHistory(
                $existingSubscription,
                'cancelled',
                notes: 'Auto-cancelled on new subscription'
            );
        }

        $price = $this->subscriptionService->getPlanPrice($plan, $data['billing_period']);
        $expiryDate = $this->subscriptionService->getExpiryDate($data['billing_period']);

        $subscription = Subscription::query()->create([
            'tenant_id' => $tenantId,
            'plan_id' => $plan->id,
            'started_at' => now(),
            'expired_at' => $expiryDate,
            'status' => 'active',
        ]);

        $this->subscriptionService->logHistory($subscription, 'subscribed');

        if ($price > 0) {
            $this->createPaymentAction->execute($user, [
                'items' => [
                    [
                        'item_type' => 'subscription',
                        'item_id' => $subscription->id,
                        'name' => "Langganan {$plan->name} ({$data['billing_period']})",
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
