<?php

declare(strict_types=1);

namespace App\Modules\Guest\Actions;

use App\Core\Base\Action;
use App\Modules\Guest\Models\Guest;
use Illuminate\Http\Request;

class GetGuestAction extends Action
{
    public function execute(mixed ...$params): ?Guest
    {
        $request = $params[0];
        $uuid = $params[1];

        $user = $request->user();

        return Guest::query()
            ->forUser($user->id)
            ->with('rsvp')
            ->where('uuid', $uuid)
            ->first();
    }
}
