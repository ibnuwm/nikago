<?php

declare(strict_types=1);

namespace App\Modules\RSVP\Actions;

use App\Core\Base\Action;
use App\Modules\Guest\Models\Guest;
use App\Modules\RSVP\Models\Rsvp;
use App\Modules\RSVP\Models\RsvpLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CreateRsvpAction extends Action
{
    public function execute(mixed ...$params): Rsvp
    {
        /** @var Request $request */
        $request = $params[0];

        $user = $request->user();

        $guestUuid = $request->input('guest_uuid');
        $guest = Guest::where('uuid', $guestUuid)
            ->where('tenant_id', $user->tenant_id)
            ->firstOrFail();

        return DB::transaction(function () use ($request, $user, $guest): Rsvp {
            $existingRsvp = Rsvp::where('guest_id', $guest->id)->first();

            if ($existingRsvp) {
                $oldAttendance = $existingRsvp->attendance;

                $existingRsvp->update([
                    'attendance' => $request->input('attendance'),
                    'total_guest' => $request->input('total_guest', 1),
                    'message' => $request->input('message'),
                    'confirmed_at' => now(),
                ]);

                RsvpLog::create([
                    'rsvp_id' => $existingRsvp->id,
                    'old_status' => $oldAttendance,
                    'new_status' => $request->input('attendance'),
                ]);

                return $existingRsvp->fresh(['guest']);
            }

            $rsvp = Rsvp::create([
                'tenant_id' => $user->tenant_id,
                'guest_id' => $guest->id,
                'attendance' => $request->input('attendance'),
                'total_guest' => $request->input('total_guest', 1),
                'message' => $request->input('message'),
            ]);

            RsvpLog::create([
                'rsvp_id' => $rsvp->id,
                'old_status' => null,
                'new_status' => $request->input('attendance'),
            ]);

            return $rsvp->load('guest');
        });
    }
}
