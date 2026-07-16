<?php

declare(strict_types=1);

namespace App\Modules\Seating\Actions;

use App\Core\Base\Action;
use App\Modules\Guest\Models\Guest;
use App\Modules\Seating\Models\SeatingAssignment;
use App\Modules\Seating\Models\SeatingTable;
use Illuminate\Http\Request;

class AssignGuestAction extends Action
{
    public function execute(mixed ...$params): ?SeatingTable
    {
        $request = $params[0];
        $uuid = $params[1];
        $user = $request->user();

        $table = SeatingTable::query()
            ->forUser($user->id)
            ->where('uuid', $uuid)
            ->first();

        if (! $table) {
            return null;
        }

        $guest = Guest::query()
            ->forUser($user->id)
            ->where('uuid', $request->input('guest_id'))
            ->first();

        if (! $guest) {
            return null;
        }

        if ($table->isFull()) {
            return null;
        }

        SeatingAssignment::create([
            'tenant_id' => $user->tenant_id ?? 1,
            'table_id' => $table->id,
            'guest_id' => $guest->id,
            'seat_number' => $request->input('seat_number'),
            'notes' => $request->input('notes'),
        ]);

        return $table->fresh()->load(['assignments' => function ($query): void {
            $query->with('guest');
        }]);
    }
}
