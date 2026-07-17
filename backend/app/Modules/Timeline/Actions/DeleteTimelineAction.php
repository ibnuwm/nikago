<?php

declare(strict_types=1);

namespace App\Modules\Timeline\Actions;

use App\Core\Base\Action;
use App\Modules\Timeline\Models\Timeline;

class DeleteTimelineAction extends Action
{
    public function execute(mixed ...$params): bool
    {
        $request = $params[0];
        $uuid = $params[1];
        $user = $request->user();

        $timeline = Timeline::query()
            ->forUser($user->id)
            ->where('uuid', $uuid)
            ->first();

        if (! $timeline) {
            return false;
        }

        $timeline->delete();

        return true;
    }
}
