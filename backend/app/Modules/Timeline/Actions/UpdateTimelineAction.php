<?php

declare(strict_types=1);

namespace App\Modules\Timeline\Actions;

use App\Core\Base\Action;
use App\Modules\Timeline\Models\Timeline;
use Illuminate\Http\Request;

class UpdateTimelineAction extends Action
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

        $timeline->update([
            'title' => $request->input('title', $timeline->title),
            'description' => $request->input('description', $timeline->description),
        ]);

        return $timeline->fresh()->load(['tasks' => function ($query): void {
            $query->orderBy('sort_order');
        }]);
    }
}
