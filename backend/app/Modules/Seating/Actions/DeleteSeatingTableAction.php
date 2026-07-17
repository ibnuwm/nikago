<?php

declare(strict_types=1);

namespace App\Modules\Seating\Actions;

use App\Core\Base\Action;
use App\Modules\Seating\Models\SeatingTable;

class DeleteSeatingTableAction extends Action
{
    public function execute(mixed ...$params): bool
    {
        $request = $params[0];
        $uuid = $params[1];
        $user = $request->user();

        $table = SeatingTable::query()
            ->forUser($user->id)
            ->where('uuid', $uuid)
            ->first();

        if (! $table) {
            return false;
        }

        $table->delete();

        return true;
    }
}
