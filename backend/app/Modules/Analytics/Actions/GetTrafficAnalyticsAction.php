<?php

declare(strict_types=1);

namespace App\Modules\Analytics\Actions;

use App\Modules\Analytics\Models\AnalyticsEvent;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GetTrafficAnalyticsAction
{
    public function execute(Request $request): array
    {
        $startDate = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))
            : Carbon::now()->subMonth();
        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))
            : Carbon::now();

        $pageViews = AnalyticsEvent::where('event_type', 'page_view')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $uniqueVisitors = AnalyticsEvent::where('event_type', 'page_view')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->distinct('user_id')
            ->count('user_id');

        $totalEvents = AnalyticsEvent::whereBetween('created_at', [$startDate, $endDate])->count();

        $byEventType = AnalyticsEvent::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('event_type, COUNT(*) as count')
            ->groupBy('event_type')
            ->pluck('count', 'event_type')
            ->toArray();

        $dailyTraffic = AnalyticsEvent::where('event_type', 'page_view')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as views, COUNT(DISTINCT user_id) as visitors')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();

        return [
            'page_views' => $pageViews,
            'unique_visitors' => $uniqueVisitors,
            'total_events' => $totalEvents,
            'by_event_type' => $byEventType,
            'daily' => $dailyTraffic,
        ];
    }
}
