<?php

declare(strict_types=1);

namespace App\Modules\RSVP\Actions;

use App\Core\Base\Action;
use App\Modules\Guest\Models\Guest;
use App\Modules\RSVP\Models\Rsvp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportRsvpsAction extends Action
{
    public function execute(mixed ...$params): StreamedResponse
    {
        /** @var Request $request */
        $request = $params[0];

        $user = $request->user();
        /** @var int $tenantId */
        $tenantId = $user->tenant_id;

        $filename = 'rsvps_export_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return new StreamedResponse(function () use ($tenantId): void {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Guest Name', 'Phone', 'Email', 'Attendance', 'Total Guest', 'Message', 'Confirmed At']);

            Rsvp::query()
                ->forTenant($tenantId)
                ->with('guest')
                ->orderBy('created_at', 'desc')
                ->chunk(100, function ($rsvps) use ($handle): void {
                    /** @var Rsvp $rsvp */
                    foreach ($rsvps as $rsvp) {
                        /** @var Guest|null $guest */
                        $guest = $rsvp->guest;
                        fputcsv($handle, [
                            $guest?->name,
                            $guest?->phone,
                            $guest?->email,
                            $rsvp->attendance,
                            $rsvp->total_guest,
                            $rsvp->message,
                            $rsvp->confirmed_at instanceof Carbon ? $rsvp->confirmed_at->format('Y-m-d H:i:s') : null,
                        ]);
                    }
                });

            fclose($handle);
        }, 200, $headers);
    }
}
