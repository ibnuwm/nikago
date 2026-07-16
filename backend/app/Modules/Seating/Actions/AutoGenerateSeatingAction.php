<?php

declare(strict_types=1);

namespace App\Modules\Seating\Actions;

use App\Core\Base\Action;
use App\Modules\Guest\Models\Guest;
use App\Modules\Seating\Models\SeatingAssignment;
use App\Modules\Seating\Models\SeatingTable;
use Illuminate\Http\Request;

class AutoGenerateSeatingAction extends Action
{
    public function execute(mixed ...$params): array
    {
        $request = $params[0];
        $user = $request->user();

        $weddingId = $request->input('wedding_id');

        $tables = SeatingTable::query()
            ->forUser($user->id)
            ->when($weddingId, function ($query, $id): void {
                $query->forWedding((int) $id);
            })
            ->orderBy('capacity', 'desc')
            ->get();

        if ($tables->isEmpty()) {
            return ['success' => false, 'message' => 'No tables found.'];
        }

        $guestQuery = Guest::query()->forUser($user->id);

        if ($weddingId) {
            $guestQuery->forWedding((int) $weddingId);
        }

        $alreadyAssignedGuestIds = SeatingAssignment::query()
            ->whereIn('table_id', $tables->pluck('id'))
            ->pluck('guest_id')
            ->toArray();

        $guests = $guestQuery
            ->whereNotIn('id', $alreadyAssignedGuestIds)
            ->inRandomOrder()
            ->get();

        if ($guests->isEmpty()) {
            return ['success' => true, 'message' => 'No unassigned guests found.', 'assigned' => 0];
        }

        $assigned = 0;
        $guestIndex = 0;

        foreach ($tables as $table) {
            $available = $table->capacity - $table->assignments()->count();

            for ($i = 0; $i < $available && $guestIndex < $guests->count(); $i++) {
                SeatingAssignment::create([
                    'tenant_id' => $user->tenant_id ?? 1,
                    'table_id' => $table->id,
                    'guest_id' => $guests[$guestIndex]->id,
                    'seat_number' => $i + 1,
                ]);

                $assigned++;
                $guestIndex++;
            }
        }

        return [
            'success' => true,
            'message' => "Auto-generated seating for {$assigned} guests.",
            'assigned' => $assigned,
        ];
    }
}
