<?php

declare(strict_types=1);

namespace App\Modules\Notification\Services\Channels;

use App\Modules\Authentication\Models\User;
use App\Modules\Notification\Services\Contracts\NotificationChannel;
use Illuminate\Support\Facades\Log;

class WhatsAppChannel implements NotificationChannel
{
    public function send(int $userId, string $title, string $message, array $data = []): void
    {
        $user = User::find($userId);

        if (!$user || !$user->phone) {
            return;
        }

        Log::info('WhatsApp notification stub', [
            'phone' => $user->phone,
            'message' => $message,
        ]);
    }
}
