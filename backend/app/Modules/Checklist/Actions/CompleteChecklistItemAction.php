<?php

declare(strict_types=1);

namespace App\Modules\Checklist\Actions;

use App\Core\Base\Action;
use App\Modules\Checklist\Models\Checklist;

class CompleteChecklistItemAction extends Action
{
    public function execute(mixed ...$params): ?Checklist
    {
        $request = $params[0];
        $uuid = $params[1];
        $user = $request->user();

        $checklist = Checklist::query()
            ->forUser($user->id)
            ->where('uuid', $uuid)
            ->first();

        if (! $checklist) {
            return null;
        }

        $item = $checklist->items()
            ->where('uuid', $request->input('item_uuid'))
            ->first();

        if (! $item) {
            return null;
        }

        $item->update(['completed_at' => now()]);

        $checklist->recalculateProgress();

        return $checklist->fresh()->load(['items' => function ($query): void {
            $query->orderBy('sort_order');
        }]);
    }
}
