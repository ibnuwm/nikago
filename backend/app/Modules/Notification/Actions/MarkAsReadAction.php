<?php

declare(strict_types=1);

namespace App\Modules\Notification\Actions;

use App\Modules\Notification\Models\Notification;
use App\Modules\Notification\Resources\NotificationResource;
use Illuminate\Contracts\Auth\Authenticatable;

class MarkAsReadAction
{
    public function execute(Authenticatable $user, string $uuid): NotificationResource
    {
        $notification = Notification::query()
            ->forUser($user->id)
            ->where('uuid', $uuid)
            ->firstOrFail();

        if (!$notification->is_read) {
            $notification->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        return new NotificationResource($notification);
    }
}
