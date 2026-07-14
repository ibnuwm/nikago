<?php

declare(strict_types=1);

namespace App\Modules\RSVP\Actions;

use App\Core\Base\Action;
use App\Modules\Guest\Models\Guest;
use App\Modules\RSVP\Models\Rsvp;
use App\Modules\RSVP\Models\RsvpLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImportRsvpsAction extends Action
{
    public function execute(mixed ...$params): array
    {
        /** @var Request $request */
        $request = $params[0];

        $user = $request->user();
        $tenantId = $user->tenant_id;
        $file = $request->file('file');

        if (! $file) {
            return ['imported' => 0, 'errors' => ['No file provided.']];
        }

        $imported = 0;
        $errors = [];

        $rows = $this->parseCsv($file->getRealPath());

        foreach ($rows as $index => $row) {
            try {
                $guestName = $row['name'] ?? $row[0] ?? null;
                $attendance = $row['attendance'] ?? $row[1] ?? null;
                $totalGuest = $row['total_guest'] ?? $row[2] ?? 1;
                $message = $row['message'] ?? $row[3] ?? null;

                if (! $guestName || ! $attendance) {
                    $errors[] = "Row {$index}: Missing required fields.";

                    continue;
                }

                $attendance = strtoupper($attendance);

                if (! in_array($attendance, Rsvp::attendances(), true)) {
                    $errors[] = "Row {$index}: Invalid attendance value.";

                    continue;
                }

                $guest = Guest::where('tenant_id', $tenantId)
                    ->whereRaw('LOWER(name) = ?', [strtolower($guestName)])
                    ->first();

                if (! $guest) {
                    $errors[] = "Row {$index}: Guest '{$guestName}' not found.";

                    continue;
                }

                DB::transaction(function () use ($guest, $attendance, $totalGuest, $message, $tenantId): void {
                    $existingRsvp = Rsvp::where('guest_id', $guest->id)
                        ->lockForUpdate()
                        ->first();

                    if ($existingRsvp) {
                        $oldAttendance = $existingRsvp->attendance;

                        $existingRsvp->update([
                            'attendance' => $attendance,
                            'total_guest' => $totalGuest,
                            'message' => $message,
                            'confirmed_at' => now(),
                        ]);

                        RsvpLog::create([
                            'rsvp_id' => $existingRsvp->id,
                            'old_status' => $oldAttendance,
                            'new_status' => $attendance,
                        ]);
                    } else {
                        $rsvp = Rsvp::create([
                            'tenant_id' => $tenantId,
                            'guest_id' => $guest->id,
                            'attendance' => $attendance,
                            'total_guest' => $totalGuest,
                            'message' => $message,
                        ]);

                        RsvpLog::create([
                            'rsvp_id' => $rsvp->id,
                            'old_status' => null,
                            'new_status' => $attendance,
                        ]);
                    }
                });

                $imported++;
            } catch (\Throwable $e) {
                $errors[] = "Row {$index}: " . $e->getMessage();
            }
        }

        return ['imported' => $imported, 'errors' => $errors];
    }

    private function parseCsv(string $path): array
    {
        $handle = fopen($path, 'r');
        if ($handle === false) {
            return [];
        }

        $headers = fgetcsv($handle);
        $rows = [];

        while (($row = fgetcsv($handle)) !== false) {
            if ($headers) {
                $rows[] = array_combine($headers, $row);
            } else {
                $rows[] = $row;
            }
        }

        fclose($handle);

        return $rows;
    }
}
