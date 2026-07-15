<?php

declare(strict_types=1);

namespace App\Modules\Planner\Actions;

use App\Core\Base\Action;

class GetPlannerProgressAction extends Action
{
    public function execute(mixed ...$params): array
    {
        $totalTask = 0;
        $completedTask = 0;

        $progress = $totalTask > 0
            ? round(($completedTask / $totalTask) * 100, 2)
            : 0.0;

        return [
            'progress' => $progress,
            'completed_task' => $completedTask,
            'total_task' => $totalTask,
        ];
    }
}
