<?php

declare(strict_types=1);

namespace App\Modules\Seating\Actions;

use App\Core\Base\Action;
use App\Modules\Seating\Models\SeatingTable;
use App\Modules\Wedding\Models\Wedding;

class CreateSeatingTableAction extends Action
{
    public function execute(mixed ...$params): ?SeatingTable
    {
        $request = $params[0];
        $user = $request->user();

        $wedding = Wedding::query()
            ->forUser($user->id)
            ->find($request->input('wedding_id'));

        if (! $wedding) {
            return null;
        }

        return SeatingTable::create([
            'tenant_id' => $user->tenant_id ?? 1,
            'wedding_id' => $wedding->id,
            'name' => $request->input('name'),
            'capacity' => $request->input('capacity', 8),
            'shape' => $request->input('shape', 'round'),
            'position_x' => $request->input('position_x'),
            'position_y' => $request->input('position_y'),
            'sort_order' => $request->input('sort_order', 0),
        ]);
    }
}
