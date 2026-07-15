<?php

declare(strict_types=1);

namespace App\Modules\Guest\Actions;

use App\Core\Base\Action;
use App\Modules\Guest\Models\Guest;
use Illuminate\Http\Request;

class CheckInGuestAction extends Action
{
    public function execute(mixed ...$params): ?Guest
    {
        $request = $params[0];
        $uuid = $params[1];

        $user = $request->user();

        $guest = Guest::query()
            ->forUser($user->id)
            ->where('uuid', $uuid)
            ->where('qr_code', $request->input('qr_code'))
            ->first();

        if (! $guest) {
            return null;
        }

        if ($guest->invitation_sent_at) {
            return $guest;
        }

        $guest->update([
            'invitation_sent_at' => now(),
        ]);

        return $guest->fresh();
    }
}
