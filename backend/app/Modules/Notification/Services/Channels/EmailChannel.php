<?php

declare(strict_types=1);

namespace App\Modules\Notification\Services\Channels;

use App\Modules\Authentication\Models\User;
use App\Modules\Notification\Services\Contracts\NotificationChannel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailChannel implements NotificationChannel
{
    public function send(int $userId, string $title, string $message, array $data = []): void
    {
        $user = User::find($userId);

        if (!$user || !$user->email) {
            return;
        }

        try {
            Mail::raw($message, function (\Illuminate\Mail\Message $mail) use ($user, $title): void {
                $mail->to($user->email)
                    ->subject($title);
            });
        } catch (\Throwable $e) {
            Log::error('Email notification failed', [
                'user_id' => $userId,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
