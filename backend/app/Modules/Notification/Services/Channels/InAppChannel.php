<?php

declare(strict_types=1);

namespace App\Modules\Notification\Services\Channels;

use App\Modules\Notification\Models\Notification;
use App\Modules\Notification\Services\Contracts\NotificationChannel;

class InAppChannel implements NotificationChannel
{
    public function send(int $userId, string $title, string $message, array $data = []): void
    {
        Notification::query()->create([
            'user_id' => $userId,
            'type' => $data['type'] ?? 'general',
            'title' => $title,
            'message' => $message,
            'channel' => 'in_app',
            'data' => $data,
        ]);
    }
}
