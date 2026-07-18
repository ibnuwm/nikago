<?php

declare(strict_types=1);

namespace App\Modules\Notification\Actions;

use App\Modules\Notification\Models\Notification;
use Illuminate\Contracts\Auth\Authenticatable;

class DeleteNotificationAction
{
    public function execute(Authenticatable $user, string $uuid): void
    {
        Notification::query()
            ->forUser($user->id)
            ->where('uuid', $uuid)
            ->firstOrFail()
            ->delete();
    }
}
