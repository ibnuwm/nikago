<?php

declare(strict_types=1);

namespace App\Modules\Dashboard\Actions;

use App\Core\Base\Action;
use App\Modules\Guest\Models\Guest;
use App\Modules\RSVP\Models\Rsvp;
use App\Modules\Wedding\Models\Wedding;

class GetRecentActivityAction extends Action
{
    public function execute(mixed ...$params): array
    {
        /** @var Wedding|null $wedding */
        $wedding = $params[0] ?? null;

        if ($wedding === null) {
            return [];
        }

        $activities = collect();

        $recentGuests = Guest::where('wedding_id', $wedding->id)
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn (Guest $guest) => [
                'id' => 'guest-' . $guest->uuid,
                'type' => 'guest',
                'title' => 'Guest added: ' . $guest->name,
                'description' => $guest->email ?? $guest->phone ?? null,
                'created_at' => $guest->created_at->format('Y-m-d H:i:s'),
            ]);

        $activities = $activities->concat($recentGuests);

        $recentRsvps = Rsvp::whereHas('guest', function ($q) use ($wedding): void {
            $q->where('wedding_id', $wedding->id);
        })
            ->latest()
            ->limit(5)
            ->get()
            ->map(function (Rsvp $rsvp): array {
                /** @var Guest $guest */
                $guest = $rsvp->guest;
                $guestName = $guest->name ?? 'Unknown';
                $status = $rsvp->attendance === Rsvp::ATTENDANCE_YES
                    ? 'confirmed attendance'
                    : ($rsvp->attendance === Rsvp::ATTENDANCE_NO
                        ? 'declined'
                        : 'responded');

                return [
                    'id' => 'rsvp-' . $rsvp->uuid,
                    'type' => 'rsvp',
                    'title' => "{$guestName} {$status}",
                    'description' => $rsvp->message ? substr($rsvp->message, 0, 80) : null,
                    'created_at' => $rsvp->created_at->format('Y-m-d H:i:s'),
                ];
            });

        $activities = $activities->concat($recentRsvps);

        return $activities
            ->sortByDesc('created_at')
            ->take(10)
            ->values()
            ->toArray();
    }
}
