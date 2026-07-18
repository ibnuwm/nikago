<?php

declare(strict_types=1);

namespace App\Modules\Analytics\Actions;

use App\Modules\Guest\Models\Guest;
use App\Modules\RSVP\Models\Rsvp;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GetRsvpAnalyticsAction
{
    public function execute(Request $request): array
    {
        $startDate = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))
            : Carbon::now()->subMonth();
        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))
            : Carbon::now();

        $totalGuests = Guest::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalRsvps = Rsvp::whereBetween('created_at', [$startDate, $endDate])->count();
        $confirmed = Rsvp::where('attendance', Rsvp::ATTENDANCE_YES)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $declined = Rsvp::where('attendance', Rsvp::ATTENDANCE_NO)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $maybe = Rsvp::where('attendance', Rsvp::ATTENDANCE_MAYBE)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $rsvpRate = $totalGuests > 0 ? round(($totalRsvps / $totalGuests) * 100, 2) : 0;

        $byAttendance = Rsvp::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('attendance, COUNT(*) as count')
            ->groupBy('attendance')
            ->pluck('count', 'attendance')
            ->toArray();

        $trend = Rsvp::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();

        return [
            'total_guests' => $totalGuests,
            'total_rsvps' => $totalRsvps,
            'confirmed' => $confirmed,
            'declined' => $declined,
            'maybe' => $maybe,
            'rsvp_rate' => $rsvpRate,
            'by_attendance' => $byAttendance,
            'trend' => $trend,
        ];
    }
}
