<?php

declare(strict_types=1);

namespace App\Modules\Subscription\Actions;

use App\Modules\Subscription\Models\Subscription;
use App\Modules\Subscription\Resources\SubscriptionResource;
use App\Modules\Subscription\Services\SubscriptionService;
use Illuminate\Contracts\Auth\Authenticatable;

class CancelSubscriptionAction
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

        $subscription->auto_renew = false;
        $subscription->cancelled_at = now();
        $subscription->save();

        $this->subscriptionService->logHistory(
            $subscription,
            'cancelled',
            notes: $data['reason'] ?? null,
        );

        return new SubscriptionResource(
            $subscription->load(['plan.features', 'plan.limits', 'histories'])
        );
    }
}
