<?php

declare(strict_types=1);

namespace App\Modules\Seating\Actions;

use App\Core\Base\Action;
use App\Modules\Seating\Models\SeatingTable;

class UpdateSeatingTableAction extends Action
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

        $table->update([
            'name' => $request->input('name', $table->name),
            'capacity' => $request->input('capacity', $table->capacity),
            'shape' => $request->input('shape', $table->shape),
            'position_x' => $request->input('position_x', $table->position_x),
            'position_y' => $request->input('position_y', $table->position_y),
            'sort_order' => $request->input('sort_order', $table->sort_order),
        ]);

        return $table->fresh();
    }
}
