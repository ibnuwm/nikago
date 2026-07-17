<?php

declare(strict_types=1);

namespace App\Modules\Rundown\Actions;

use App\Core\Base\Action;
use App\Modules\Rundown\Models\Rundown;
use App\Modules\Rundown\Models\RundownItem;
use App\Modules\Wedding\Models\Wedding;
use Illuminate\Http\Request;

class CreateRundownAction extends Action
{
    public function execute(mixed ...$params): ?Rundown
    {
        $request = $params[0];
        $user = $request->user();

        $wedding = Wedding::query()
            ->forUser($user->id)
            ->find($request->input('wedding_id'));

        if (! $wedding) {
            return null;
        }

        $rundown = Rundown::create([
            'tenant_id' => $user->tenant_id ?? 1,
            'wedding_id' => $wedding->id,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ]);

        if ($request->has('items')) {
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
