<?php

declare(strict_types=1);

namespace App\Modules\Timeline\Actions;

use App\Core\Base\Action;
use App\Modules\Timeline\Models\Timeline;

class SyncGoogleCalendarAction extends Action
{
    public function execute(mixed ...$params): ?Timeline
    {
        $request = $params[0];
        $uuid = $params[1];
        $user = $request->user();

        $timeline = Timeline::query()
            ->forUser($user->id)
            ->with(['tasks' => function ($query): void {
                $query->orderBy('sort_order');
            }])
            ->where('uuid', $uuid)
            ->first();

        if (! $timeline) {
            return null;
        }

        return $timeline;
    }
}
