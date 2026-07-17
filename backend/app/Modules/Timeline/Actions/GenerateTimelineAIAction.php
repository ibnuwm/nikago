<?php

declare(strict_types=1);

namespace App\Modules\Timeline\Actions;

use App\Core\Base\Action;
use App\Modules\Timeline\Models\Timeline;
use App\Modules\Timeline\Models\TimelineTask;
use App\Modules\Wedding\Models\Wedding;

class GenerateTimelineAIAction extends Action
{
    public function execute(mixed ...$params): ?Timeline
    {
        $request = $params[0];
        $user = $request->user();

        $wedding = Wedding::query()
            ->forUser($user->id)
            ->first();

        if (! $wedding) {
            return null;
        }

        $timeline = Timeline::create([
            'tenant_id' => $user->tenant_id ?? 1,
            'wedding_id' => $wedding->id,
            'title' => 'AI Generated Timeline',
            'description' => 'Automatically generated timeline for your wedding preparation.',
        ]);

        $tasks = [
            ['title' => 'Book venue', 'priority' => TimelineTask::PRIORITY_HIGH, 'duration_days' => 1, 'sort_order' => 0],
            ['title' => 'Choose caterer', 'priority' => TimelineTask::PRIORITY_HIGH, 'duration_days' => 1, 'sort_order' => 1],
            ['title' => 'Select wedding dress', 'priority' => TimelineTask::PRIORITY_HIGH, 'duration_days' => 1, 'sort_order' => 2],
            ['title' => 'Book photographer', 'priority' => TimelineTask::PRIORITY_MEDIUM, 'duration_days' => 1, 'sort_order' => 3],
            ['title' => 'Hire makeup artist', 'priority' => TimelineTask::PRIORITY_MEDIUM, 'duration_days' => 1, 'sort_order' => 4],
            ['title' => 'Send invitations', 'priority' => TimelineTask::PRIORITY_MEDIUM, 'duration_days' => 1, 'sort_order' => 5],
            ['title' => 'Arrange decorations', 'priority' => TimelineTask::PRIORITY_MEDIUM, 'duration_days' => 1, 'sort_order' => 6],
            ['title' => 'Wedding rehearsal', 'priority' => TimelineTask::PRIORITY_HIGH, 'duration_days' => 1, 'sort_order' => 7],
            ['title' => 'Confirm guest list', 'priority' => TimelineTask::PRIORITY_HIGH, 'duration_days' => 1, 'sort_order' => 8],
            ['title' => 'Book honeymoon', 'priority' => TimelineTask::PRIORITY_LOW, 'duration_days' => 1, 'sort_order' => 9],
        ];

        foreach ($tasks as $taskData) {
            TimelineTask::create([
                'timeline_id' => $timeline->id,
                'title' => $taskData['title'],
                'priority' => $taskData['priority'],
                'duration_days' => $taskData['duration_days'],
                'sort_order' => $taskData['sort_order'],
            ]);
        }

        return $timeline->fresh()->load(['tasks' => function ($query): void {
            $query->orderBy('sort_order');
        }]);
    }
}
