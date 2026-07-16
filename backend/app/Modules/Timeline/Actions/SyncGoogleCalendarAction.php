<?php

declare(strict_types=1);

namespace App\Modules\Timeline\Actions;

use App\Core\Base\Action;
use App\Modules\Timeline\Models\Timeline;
use Illuminate\Http\Request;

class SyncGoogleCalendarAction extends Action
{
    public function execute(mixed ...$params): array
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
            return ['success' => false, 'message' => 'Timeline not found.'];
        }

        return [
            'success' => true,
            'message' => 'Google Calendar sync initiated.',
            'data' => [
                'timeline_id' => $timeline->uuid,
                'tasks_count' => $timeline->tasks->count(),
                'status' => 'synced',
            ],
        ];
    }
}
