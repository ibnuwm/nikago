<?php

declare(strict_types=1);

namespace App\Modules\Analytics\Actions;

use App\Modules\Invitation\Models\Invitation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GetInvitationAnalyticsAction
{
    public function execute(Request $request): array
    {
        $startDate = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))
            : Carbon::now()->subMonth();
        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))
            : Carbon::now();

        $total = Invitation::whereBetween('created_at', [$startDate, $endDate])->count();
        $published = Invitation::where('status', 'published')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $draft = Invitation::where('status', 'draft')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $byStatus = Invitation::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $trend = Invitation::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();

        return [
            'total_invitations' => $total,
            'published' => $published,
            'draft' => $draft,
            'by_status' => $byStatus,
            'trend' => $trend,
        ];
    }
}
