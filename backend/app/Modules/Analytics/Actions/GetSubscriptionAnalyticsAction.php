<?php

declare(strict_types=1);

namespace App\Modules\Analytics\Actions;

use App\Modules\Subscription\Models\Subscription;
use App\Modules\Subscription\Models\SubscriptionPlan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GetSubscriptionAnalyticsAction
{
    public function execute(Request $request): array
    {
        $startDate = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))
            : Carbon::now()->subMonth();
        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))
            : Carbon::now();

        $totalSubscriptions = Subscription::whereBetween('created_at', [$startDate, $endDate])->count();
        $active = Subscription::where('status', 'active')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $expired = Subscription::where('status', 'expired')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $cancelled = Subscription::where('status', 'cancelled')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $trialing = Subscription::where('status', 'trialing')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $newSubscriptions = Subscription::whereBetween('created_at', [$startDate, $endDate])->count();

        $totalSubscribers = Subscription::where('status', 'active')->count();
        $churned = Subscription::where('status', 'cancelled')
            ->whereBetween('cancelled_at', [$startDate, $endDate])
            ->count();
        $churnRate = $totalSubscribers + $churned > 0
            ? round(($churned / ($totalSubscribers + $churned)) * 100, 2)
            : 0;

        $byPlan = SubscriptionPlan::withCount(['subscriptions' => function ($query) use ($startDate, $endDate): void {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }])->get()->map(fn (SubscriptionPlan $plan): array => [
            'plan' => $plan->name,
            'count' => $plan->subscriptions_count,
        ])->toArray();

        $mrr = Subscription::where('status', 'active')
            ->join('subscription_plans', 'subscriptions.plan_id', '=', 'subscription_plans.id')
            ->sum('subscription_plans.monthly_price');

        $trend = Subscription::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();

        return [
            'total_subscriptions' => $totalSubscriptions,
            'active' => $active,
            'expired' => $expired,
            'cancelled' => $cancelled,
            'trialing' => $trialing,
            'new_subscriptions' => $newSubscriptions,
            'churn_rate' => $churnRate,
            'mrr' => (float) $mrr,
            'arr' => (float) $mrr * 12,
            'by_plan' => $byPlan,
            'trend' => $trend,
        ];
    }
}
