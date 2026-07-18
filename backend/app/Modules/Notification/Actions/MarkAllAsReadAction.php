<?php

declare(strict_types=1);

namespace App\Modules\Notification\Actions;

use App\Modules\Notification\Models\Notification;
use Illuminate\Contracts\Auth\Authenticatable;

class MarkAllAsReadAction
{
    public function execute(Authenticatable $user): array
    {
        $count = Notification::query()
            ->forUser($user->id)
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return ['updated' => $count];
    }
}
