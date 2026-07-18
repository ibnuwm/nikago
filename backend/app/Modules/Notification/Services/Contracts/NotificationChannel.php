<?php

declare(strict_types=1);

namespace App\Modules\Notification\Services\Contracts;

interface NotificationChannel
{
    public function send(int $userId, string $title, string $message, array $data = []): void;
}
