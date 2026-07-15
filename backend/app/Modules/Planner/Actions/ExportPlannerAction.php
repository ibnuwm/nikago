<?php

declare(strict_types=1);

namespace App\Modules\Planner\Actions;

use App\Core\Base\Action;
use App\Modules\Wedding\Models\Wedding;
use Illuminate\Http\Request;

class ExportPlannerAction extends Action
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
            'wedding_title' => $wedding?->title ?? 'My Wedding',
            'exported_at' => now()->toIsoString(),
            'progress' => $progress,
            'summary' => $summary,
        ];
    }
}
