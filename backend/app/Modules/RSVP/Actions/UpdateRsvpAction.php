<?php

declare(strict_types=1);

namespace App\Modules\RSVP\Actions;

use App\Core\Base\Action;
use App\Modules\RSVP\Models\Rsvp;
use App\Modules\RSVP\Models\RsvpLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UpdateRsvpAction extends Action
{
    public function execute(mixed ...$params): ?Rsvp
    {
        /** @var Request $request */
        $request = $params[0];
        /** @var string $uuid */
        $uuid = $params[1];

        $user = $request->user();

        $rsvp = Rsvp::query()
            ->where('uuid', $uuid)
            ->where('tenant_id', $user->tenant_id)
            ->first();

        if (! $rsvp) {
            return null;
        }

        return DB::transaction(function () use ($request, $rsvp): Rsvp {
            $oldAttendance = $rsvp->attendance;

            $rsvp->update([
                'attendance' => $request->input('attendance', $rsvp->attendance),
                'total_guest' => $request->input('total_guest', $rsvp->total_guest),
                'message' => $request->input('message', $rsvp->message),
                'confirmed_at' => now(),
            ]);

            $newAttendance = $rsvp->fresh()->attendance;

            if ($oldAttendance !== $newAttendance) {
                RsvpLog::create([
                    'rsvp_id' => $rsvp->id,
                    'old_status' => $oldAttendance,
                    'new_status' => $newAttendance,
                ]);
            }

            return $rsvp->fresh('guest');
        });
    }
}
