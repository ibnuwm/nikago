<?php

declare(strict_types=1);

namespace App\Modules\Analytics\Actions;

use App\Modules\Authentication\Models\User;
use App\Modules\AI\Models\AiUsage;
use App\Modules\Payment\Models\Payment;
use App\Modules\Subscription\Models\Subscription;
use App\Modules\Vendor\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GetDashboardAnalyticsAction
{
    public function execute(Request $request): array
    {
        $startDate = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))
            : Carbon::now()->subMonth();
        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))
            : Carbon::now();

        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count();
        $newUsers = User::whereBetween('created_at', [$startDate, $endDate])->count();

        $totalVendors = Vendor::count();
        $verifiedVendors = Vendor::whereNotNull('verified_at')->count();

        $totalRevenue = Payment::where('status', 'paid')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->sum('amount');

        $activeSubscriptions = Subscription::where('status', 'active')->count();
        $mrr = Subscription::where('status', 'active')
            ->join('subscription_plans', 'subscriptions.plan_id', '=', 'subscription_plans.id')
            ->sum('subscription_plans.monthly_price');

        $totalAiTokens = AiUsage::whereBetween('created_at', [$startDate, $endDate])->sum('total_tokens');
        $totalAiCost = AiUsage::whereBetween('created_at', [$startDate, $endDate])->sum('cost');

        $previousStart = (new Carbon($startDate))->subMonth();
        $previousEnd = (new Carbon($startDate))->subDay();
        $previousRevenue = Payment::where('status', 'paid')
            ->whereBetween('paid_at', [$previousStart, $previousEnd])
            ->sum('amount');

        $revenueGrowth = $previousRevenue > 0
            ? round((($totalRevenue - $previousRevenue) / $previousRevenue) * 100, 2)
            : 0;

        return [
            'total_users' => $totalUsers,
            'active_users' => $activeUsers,
            'new_users' => $newUsers,
            'total_vendors' => $totalVendors,
            'verified_vendors' => $verifiedVendors,
            'total_revenue' => (float) $totalRevenue,
            'mrr' => (float) $mrr,
            'arr' => (float) $mrr * 12,
            'active_subscriptions' => $activeSubscriptions,
            'total_ai_tokens' => $totalAiTokens,
            'total_ai_cost' => (float) $totalAiCost,
            'growth' => [
                'revenue' => $revenueGrowth,
                'revenue_percentage' => $revenueGrowth,
            ],
        ];
    }
}
