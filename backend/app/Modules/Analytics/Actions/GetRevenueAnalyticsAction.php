<?php

declare(strict_types=1);

namespace App\Modules\Analytics\Actions;

use App\Modules\Payment\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GetRevenueAnalyticsAction
{
    public function execute(Request $request): array
    {
        $startDate = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))
            : Carbon::now()->subMonth();
        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))
            : Carbon::now();

        $totalRevenue = Payment::where('status', 'paid')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->sum('amount');

        $totalTransactions = Payment::where('status', 'paid')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->count();

        $averageTransactionValue = $totalTransactions > 0
            ? round($totalRevenue / $totalTransactions, 2)
            : 0;

        $byMethod = Payment::where('status', 'paid')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->join('payment_methods', 'payments.payment_method_id', '=', 'payment_methods.id')
            ->selectRaw('payment_methods.name as method, SUM(payments.amount) as total, COUNT(*) as count')
            ->groupBy('payment_methods.name')
            ->get()
            ->toArray();

        $dailyRevenue = Payment::where('status', 'paid')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->selectRaw('DATE(paid_at) as date, SUM(amount) as revenue, COUNT(*) as transactions')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();

        $previousPeriodStart = (new Carbon($startDate))->subDays((int) $startDate->diffInDays($endDate) + 1);
        $previousPeriodEnd = (new Carbon($startDate))->subDay();
        $previousRevenue = Payment::where('status', 'paid')
            ->whereBetween('paid_at', [$previousPeriodStart, $previousPeriodEnd])
            ->sum('amount');

        $growthPercentage = $previousRevenue > 0
            ? round((($totalRevenue - $previousRevenue) / $previousRevenue) * 100, 2)
            : 0;

        $refunds = Payment::where('status', 'refunded')
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->sum('amount');

        return [
            'total_revenue' => (float) $totalRevenue,
            'total_transactions' => $totalTransactions,
            'average_transaction_value' => (float) $averageTransactionValue,
            'growth_percentage' => $growthPercentage,
            'refunds' => (float) $refunds,
            'by_method' => $byMethod,
            'daily' => $dailyRevenue,
        ];
    }
}
