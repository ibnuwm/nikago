<?php

declare(strict_types=1);

namespace App\Modules\Seating\Actions;

use App\Core\Base\Action;
use App\Modules\Seating\Models\SeatingTable;
use Illuminate\Http\Request;

class GetSeatingTableAction extends Action
{
    public function execute(mixed ...$params): ?SeatingTable
    {
        $request = $params[0];
        $uuid = $params[1];
        $user = $request->user();

        return SeatingTable::query()
            ->forUser($user->id)
            ->with(['assignments' => function ($query): void {
                $query->with('guest');
            }])
            ->where('uuid', $uuid)
            ->first();
    }
}
