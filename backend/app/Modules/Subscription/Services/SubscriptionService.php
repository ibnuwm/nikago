<?php

declare(strict_types=1);

namespace App\Modules\Subscription\Services;

use App\Modules\Subscription\Models\Subscription;
use App\Modules\Subscription\Models\SubscriptionHistory;
use App\Modules\Subscription\Models\SubscriptionPlan;
use RuntimeException;

class SubscriptionService
{
    public function getPlanPrice(SubscriptionPlan $plan, string $billingPeriod): float
    {
        return match ($billingPeriod) {
            'monthly' => (float) $plan->monthly_price,
            'yearly' => (float) ($plan->yearly_price ?? $plan->monthly_price * 12),
            default => throw new RuntimeException("Invalid billing period: {$billingPeriod}"),
        };
    }

    public function getExpiryDate(string $billingPeriod): \DateTimeInterface
    {
        return match ($billingPeriod) {
            'monthly' => now()->addMonth(),
            'yearly' => now()->addYear(),
            default => throw new RuntimeException("Invalid billing period: {$billingPeriod}"),
        };
    }

    public function logHistory(Subscription $subscription, string $action, ?int $oldPlanId = null, ?string $notes = null): void
    {
        SubscriptionHistory::query()->create([
            'subscription_id' => $subscription->id,
            'plan_id' => $subscription->plan_id,
            'action' => $action,
            'old_plan_id' => $oldPlanId,
            'notes' => $notes,
        ]);
    }
}
