<?php

declare(strict_types=1);

namespace App\Modules\Guest\Actions;

use App\Core\Base\Action;
use App\Modules\Guest\Models\Guest;
use Illuminate\Http\Request;

class SendReminderAction extends Action
{
    public function execute(mixed ...$params): ?Guest
    {
        $request = $params[0];

        $user = $request->user();

        $guest = Guest::query()
            ->forUser($user->id)
            ->where('uuid', $request->input('guest_uuid'))
            ->first();

        if (! $guest) {
            return null;
        }

        $guest->update([
            'invitation_sent_at' => now(),
        ]);

        return $guest->fresh();
    }
}
