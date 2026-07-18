<?php

declare(strict_types=1);

namespace App\Modules\Analytics\Actions;

use App\Modules\Guest\Models\Guest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GetGuestAnalyticsAction
{
    public function execute(Request $request): array
    {
        $startDate = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))
            : Carbon::now()->subMonth();
        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))
            : Carbon::now();

        $total = Guest::whereBetween('created_at', [$startDate, $endDate])->count();
        $invited = Guest::whereNotNull('invitation_sent_at')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $notInvited = $total - $invited;

        $byStatus = Guest::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $trend = Guest::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();

        return [
            'total_guests' => $total,
            'invited' => $invited,
            'not_invited' => $notInvited,
            'by_status' => $byStatus,
            'trend' => $trend,
        ];
    }
}
