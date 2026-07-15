<?php

declare(strict_types=1);

namespace App\Modules\Checklist\Actions;

use App\Core\Base\Action;
use App\Modules\Checklist\Models\Checklist;
use App\Modules\Checklist\Models\ChecklistItem;
use Illuminate\Http\Request;

class ReorderChecklistItemsAction extends Action
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

        foreach ($request->input('items') as $itemData) {
            ChecklistItem::query()
                ->where('checklist_id', $checklist->id)
                ->where('uuid', $itemData['uuid'])
                ->update(['sort_order' => $itemData['sort_order']]);
        }

        return $checklist->fresh()->load(['items' => function ($query): void {
            $query->orderBy('sort_order');
        }]);
    }
}
