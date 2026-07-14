<?php

declare(strict_types=1);

namespace App\Modules\RSVP\Actions;

use App\Core\Base\Action;
use App\Modules\RSVP\Models\Rsvp;
use Illuminate\Http\Request;

class GetRsvpStatisticsAction extends Action
{
    public function execute(mixed ...$params): array
    {
        /** @var Request $request */
        $request = $params[0];

        $user = $request->user();
        /** @var int $tenantId */
        $tenantId = $user->tenant_id;

        $totalRsvps = Rsvp::forTenant($tenantId)->count();
        $yesCount = Rsvp::forTenant($tenantId)->attendance(Rsvp::ATTENDANCE_YES)->count();
        $noCount = Rsvp::forTenant($tenantId)->attendance(Rsvp::ATTENDANCE_NO)->count();
        $maybeCount = Rsvp::forTenant($tenantId)->attendance(Rsvp::ATTENDANCE_MAYBE)->count();
        $totalGuests = Rsvp::forTenant($tenantId)->sum('total_guest');

        $attendanceRate = $totalRsvps > 0 ? round(($yesCount / $totalRsvps) * 100, 1) : 0;

        return [
            'total_rsvps' => $totalRsvps,
            'yes' => $yesCount,
            'no' => $noCount,
            'maybe' => $maybeCount,
            'total_guests' => (int) $totalGuests,
            'attendance_rate' => $attendanceRate,
        ];
    }
}
