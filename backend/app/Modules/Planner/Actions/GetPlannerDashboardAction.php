<?php

declare(strict_types=1);

namespace App\Modules\Planner\Actions;

use App\Core\Base\Action;
use App\Modules\Wedding\Models\Wedding;

class GetPlannerDashboardAction extends Action
{
    public function execute(mixed ...$params): array
    {
        $request = $params[0];

        $user = $request->user();

        $wedding = Wedding::query()
            ->forUser($user->id)
            ->first();

        $progress = app(GetPlannerProgressAction::class)->execute($request);
        $summary = app(GetPlannerSummaryAction::class)->execute($request);

        return [
            'wedding' => $wedding,
            'progress' => $progress,
            'summary' => $summary,
        ];
    }
}
