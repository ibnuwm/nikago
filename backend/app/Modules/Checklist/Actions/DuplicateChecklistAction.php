<?php

declare(strict_types=1);

namespace App\Modules\Checklist\Actions;

use App\Core\Base\Action;
use App\Modules\Checklist\Models\Checklist;
use App\Modules\Checklist\Models\ChecklistItem;

class DuplicateChecklistAction extends Action
{
    public function execute(mixed ...$params): ?Checklist
    {
        $request = $params[0];
        $uuid = $params[1];
        $user = $request->user();

        $original = Checklist::query()
            ->forUser($user->id)
            ->with('items')
            ->where('uuid', $uuid)
            ->first();

        if (! $original) {
            return null;
        }

        $copy = Checklist::create([
            'tenant_id' => $original->tenant_id,
            'wedding_id' => $original->wedding_id,
            'title' => $original->title . ' (Copy)',
            'description' => $original->description,
        ]);

        foreach ($original->items as $item) {
            ChecklistItem::create([
                'checklist_id' => $copy->id,
                'title' => $item->title,
                'priority' => $item->priority,
                'due_date' => $item->due_date,
                'sort_order' => $item->sort_order,
            ]);
        }

        return $copy->fresh()->load(['items' => function ($query): void {
            $query->orderBy('sort_order');
        }]);
    }
}
