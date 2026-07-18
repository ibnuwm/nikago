<?php

declare(strict_types=1);

namespace App\Modules\Analytics\Actions;

use App\Modules\AI\Models\AiUsage;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GetAiAnalyticsAction
{
    public function execute(Request $request): array
    {
        $startDate = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))
            : Carbon::now()->subMonth();
        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))
            : Carbon::now();

        $totalRequests = AiUsage::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalTokens = AiUsage::whereBetween('created_at', [$startDate, $endDate])->sum('total_tokens');
        $totalCost = AiUsage::whereBetween('created_at', [$startDate, $endDate])->sum('cost');

        $averageTokensPerRequest = $totalRequests > 0 ? round($totalTokens / $totalRequests) : 0;
        $averageCostPerRequest = $totalRequests > 0 ? round($totalCost / $totalRequests, 6) : 0;

        $byFeature = AiUsage::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('feature, SUM(total_tokens) as total_tokens, SUM(cost) as cost, COUNT(*) as requests')
            ->groupBy('feature')
            ->get()
            ->toArray();

        $dailyUsage = AiUsage::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, SUM(total_tokens) as tokens, SUM(cost) as cost, COUNT(*) as requests')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();

        return [
            'total_requests' => $totalRequests,
            'total_tokens' => $totalTokens,
            'total_cost' => (float) $totalCost,
            'average_tokens_per_request' => $averageTokensPerRequest,
            'average_cost_per_request' => (float) $averageCostPerRequest,
            'by_feature' => $byFeature,
            'daily' => $dailyUsage,
        ];
    }
}
