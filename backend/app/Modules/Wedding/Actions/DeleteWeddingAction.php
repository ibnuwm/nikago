<?php

declare(strict_types=1);

namespace App\Modules\Wedding\Actions;

use App\Core\Base\Action;
use App\Modules\Wedding\Models\Wedding;
use Illuminate\Http\Request;

class DeleteWeddingAction extends Action
{
    public function execute(mixed ...$params): bool
    {
        /** @var Request $request */
        $request = $params[0];
        /** @var string $uuid */
        $uuid = $params[1];

        $user = $request->user();

        $wedding = Wedding::query()
            ->forUser($user->id)
            ->where('uuid', $uuid)
            ->first();

        if (! $wedding) {
            return false;
        }

        $wedding->delete();

        return true;
    }
}
