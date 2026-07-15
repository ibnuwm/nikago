<?php

declare(strict_types=1);

namespace App\Modules\Guest\Actions;

use App\Core\Base\Action;
use App\Modules\Guest\Models\Guest;
use Illuminate\Http\Request;

class DeleteGuestAction extends Action
{
    public function execute(mixed ...$params): bool
    {
        $request = $params[0];
        $uuid = $params[1];

        $user = $request->user();

        $guest = Guest::query()
            ->forUser($user->id)
            ->where('uuid', $uuid)
            ->first();

        if (! $guest) {
            return false;
        }

        $guest->delete();

        return true;
    }
}
