<?php

declare(strict_types=1);

namespace App\Modules\Timeline\Actions;

use App\Core\Base\Action;
use App\Modules\Timeline\Models\Timeline;
use App\Modules\Timeline\Models\TimelineTask;
use App\Modules\Wedding\Models\Wedding;

class CreateTimelineAction extends Action
{
    public function execute(mixed ...$params): ?Timeline
    {
        $request = $params[0];
        $user = $request->user();

        $wedding = Wedding::query()
            ->forUser($user->id)
            ->find($request->input('wedding_id'));

        if (! $wedding) {
            return null;
        }

        $timeline = Timeline::create([
            'tenant_id' => $user->tenant_id ?? 1,
            'wedding_id' => $wedding->id,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ]);

        if ($request->has('tasks')) {
            foreach ($request->input('tasks') as $taskData) {
                TimelineTask::create([
                    'timeline_id' => $timeline->id,
                    'title' => $taskData['title'],
                    'description' => $taskData['description'] ?? null,
                    'priority' => $taskData['priority'] ?? TimelineTask::PRIORITY_MEDIUM,
                    'start_date' => $taskData['start_date'] ?? null,
                    'due_date' => $taskData['due_date'] ?? null,
                    'duration_days' => $taskData['duration_days'] ?? 1,
                    'sort_order' => $taskData['sort_order'] ?? 0,
                ]);
            }
        }

        return $timeline->fresh()->load(['tasks' => function ($query): void {
            $query->orderBy('sort_order');
        }]);
    }
}
