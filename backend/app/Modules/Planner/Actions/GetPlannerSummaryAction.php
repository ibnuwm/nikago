<?php

declare(strict_types=1);

namespace App\Modules\Planner\Actions;

use App\Core\Base\Action;
use App\Modules\Guest\Models\Guest;
use App\Modules\Wedding\Models\Wedding;
use Illuminate\Http\Request;

class GetPlannerSummaryAction extends Action
{
    public function execute(mixed ...$params): array
    {
        $request = $params[0];

        $user = $request->user();

        $wedding = Wedding::query()
            ->forUser($user->id)
            ->first();

        $progress = app(GetPlannerProgressAction::class)->execute($request);

        return [
            'wedding_title' => $wedding?->title,
            'wedding_status' => $wedding?->status,
            'progress' => $progress['progress'],
            'completed_task' => $progress['completed_task'],
            'total_task' => $progress['total_task'],
            'guests_count' => Guest::query()->forUser($user->id)->count(),
            'checklist_count' => 0,
            'budget_total' => 0,
            'budget_spent' => 0,
            'timeline_count' => 0,
            'reminder_count' => 0,
        ];
    }
}
