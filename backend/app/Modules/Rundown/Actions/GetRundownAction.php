<?php

declare(strict_types=1);

namespace App\Modules\Rundown\Actions;

use App\Core\Base\Action;
use App\Modules\Rundown\Models\Rundown;

class GetRundownAction extends Action
{
    public function execute(mixed ...$params): ?Rundown
    {
        $request = $params[0];
        $uuid = $params[1];
        $user = $request->user();

        return Rundown::query()
            ->forUser($user->id)
            ->with(['items' => function ($query): void {
                $query->orderBy('sort_order');
            }])
            ->where('uuid', $uuid)
            ->first();
    }
}
