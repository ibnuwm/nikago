<?php

declare(strict_types=1);

namespace App\Modules\Checklist\Actions;

use App\Core\Base\Action;
use App\Modules\Checklist\Models\Checklist;

class GetChecklistAction extends Action
{
    public function execute(mixed ...$params): ?Checklist
    {
        $request = $params[0];
        $uuid = $params[1];
        $user = $request->user();

        return Checklist::query()
            ->forUser($user->id)
            ->with(['items' => function ($query): void {
                $query->orderBy('sort_order');
            }])
            ->where('uuid', $uuid)
            ->first();
    }
}
