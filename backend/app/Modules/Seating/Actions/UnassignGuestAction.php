<?php

declare(strict_types=1);

namespace App\Modules\Seating\Actions;

use App\Core\Base\Action;
use App\Modules\Seating\Models\SeatingAssignment;

class UnassignGuestAction extends Action
{
    public function execute(mixed ...$params): bool
    {
        $request = $params[0];
        $tableUuid = $params[1];
        $assignmentUuid = $params[2];
        $user = $request->user();

        $assignment = SeatingAssignment::query()
            ->where('uuid', $assignmentUuid)
            ->whereHas('table', function ($q) use ($user, $tableUuid): void {
                $q->forUser($user->id)->where('uuid', $tableUuid);
            })
            ->first();

        if (! $assignment) {
            return false;
        }

        $assignment->delete();

        return true;
    }
}
