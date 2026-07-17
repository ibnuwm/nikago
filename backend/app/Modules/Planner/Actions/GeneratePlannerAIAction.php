<?php

declare(strict_types=1);

namespace App\Modules\Planner\Actions;

use App\Core\Base\Action;
use App\Modules\Wedding\Models\Wedding;

class GeneratePlannerAIAction extends Action
{
    public function execute(mixed ...$params): array
    {
        $request = $params[0];

        $user = $request->user();

        $wedding = Wedding::query()
            ->forUser($user->id)
            ->first();

        if (! $wedding) {
            return ['success' => false, 'message' => 'No wedding found.'];
        }

        return [
            'success' => true,
            'data' => [
                'checklists' => [],
                'timelines' => [],
                'budgets' => [],
            ],
        ];
    }
}
