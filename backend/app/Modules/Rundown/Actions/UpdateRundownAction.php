<?php

declare(strict_types=1);

namespace App\Modules\Rundown\Actions;

use App\Core\Base\Action;
use App\Modules\Rundown\Models\Rundown;
use App\Modules\Rundown\Models\RundownItem;

class UpdateRundownAction extends Action
{
    public function execute(mixed ...$params): ?Rundown
    {
        $request = $params[0];
        $uuid = $params[1];
        $user = $request->user();

        $rundown = Rundown::query()
            ->forUser($user->id)
            ->where('uuid', $uuid)
            ->first();

        if (! $rundown) {
            return null;
        }

        $rundown->update([
            'title' => $request->input('title', $rundown->title),
            'description' => $request->input('description', $rundown->description),
        ]);

        if ($request->has('items')) {
            $rundown->items()->delete();

            foreach ($request->input('items') as $itemData) {
                RundownItem::create([
                    'rundown_id' => $rundown->id,
                    'title' => $itemData['title'],
                    'description' => $itemData['description'] ?? null,
                    'start_time' => $itemData['start_time'] ?? null,
                    'end_time' => $itemData['end_time'] ?? null,
                    'pic' => $itemData['pic'] ?? null,
                    'notes' => $itemData['notes'] ?? null,
                    'sort_order' => $itemData['sort_order'] ?? 0,
                ]);
            }
        }

        return $rundown->fresh()->load(['items' => function ($query): void {
            $query->orderBy('sort_order');
        }]);
    }
}
