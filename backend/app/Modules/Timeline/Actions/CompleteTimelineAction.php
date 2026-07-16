<?php

declare(strict_types=1);

namespace App\Modules\Timeline\Actions;

use App\Core\Base\Action;
use App\Modules\Timeline\Models\Timeline;
use Illuminate\Http\Request;

class CompleteTimelineAction extends Action
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

        if ($timeline->isCompleted()) {
            $timeline->update([
                'completed_at' => null,
                'progress' => $timeline->tasks()->whereNotNull('completed_at')->count() > 0
                    ? round(($timeline->tasks()->whereNotNull('completed_at')->count() / max($timeline->tasks()->count(), 1)) * 100, 2)
                    : 0,
            ]);
        } else {
            $timeline->update(['completed_at' => now(), 'progress' => 100]);
            $timeline->tasks()->whereNull('completed_at')->update(['completed_at' => now()]);
        }

        return $timeline->fresh()->load(['tasks' => function ($query): void {
            $query->orderBy('sort_order');
        }]);
    }
}
