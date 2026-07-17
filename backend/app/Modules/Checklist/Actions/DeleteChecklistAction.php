<?php

declare(strict_types=1);

namespace App\Modules\Checklist\Actions;

use App\Core\Base\Action;
use App\Modules\Checklist\Models\Checklist;

class DeleteChecklistAction extends Action
{
    public function execute(mixed ...$params): bool
    {
        $request = $params[0];
        $uuid = $params[1];
        $user = $request->user();

        $checklist = Checklist::query()
            ->forUser($user->id)
            ->where('uuid', $uuid)
            ->first();

        if (! $checklist) {
            return false;
        }

        $checklist->delete();

        return true;
    }
}
