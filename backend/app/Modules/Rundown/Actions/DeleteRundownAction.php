<?php

declare(strict_types=1);

namespace App\Modules\Rundown\Actions;

use App\Core\Base\Action;
use App\Modules\Rundown\Models\Rundown;

class DeleteRundownAction extends Action
{
    public function execute(mixed ...$params): bool
    {
        $request = $params[0];
        $uuid = $params[1];
        $user = $request->user();

        $rundown = Rundown::query()
            ->forUser($user->id)
            ->where('uuid', $uuid)
            ->first();

        if (! $rundown) {
            return false;
        }

        $rundown->delete();

        return true;
    }
}
