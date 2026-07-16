<?php

declare(strict_types=1);

namespace App\Modules\Timeline\Actions;

use App\Core\Base\Action;
use App\Modules\Timeline\Models\Timeline;
use Illuminate\Http\Request;

class UncompleteTimelineTaskAction extends Action
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

        $task = $timeline->tasks()
            ->where('uuid', $request->input('task_uuid'))
            ->first();

        if (! $task) {
            return null;
        }

        $task->update(['completed_at' => null]);

        $timeline->recalculateProgress();

        return $timeline->fresh()->load(['tasks' => function ($query): void {
            $query->orderBy('sort_order');
        }]);
    }
}
