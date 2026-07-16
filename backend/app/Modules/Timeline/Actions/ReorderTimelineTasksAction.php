<?php

declare(strict_types=1);

namespace App\Modules\Timeline\Actions;

use App\Core\Base\Action;
use App\Modules\Timeline\Models\Timeline;
use App\Modules\Timeline\Models\TimelineTask;
use Illuminate\Http\Request;

class ReorderTimelineTasksAction extends Action
{
    public function execute(mixed ...$params): ?Timeline
    {
        $request = $params[0];
        $uuid = $params[1];
        $user = $request->user();

        $timeline = Timeline::query()
            ->forUser($user->id)
            ->where('uuid', $uuid)
            ->first();

        if (! $timeline) {
            return null;
        }

        foreach ($request->input('tasks') as $taskData) {
            TimelineTask::query()
                ->where('timeline_id', $timeline->id)
                ->where('uuid', $taskData['uuid'])
                ->update(['sort_order' => $taskData['sort_order']]);
        }

        return $timeline->fresh()->load(['tasks' => function ($query): void {
            $query->orderBy('sort_order');
        }]);
    }
}
