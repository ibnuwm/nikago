<?php

declare(strict_types=1);

namespace App\Modules\Notification\Services;

use App\Modules\Notification\Models\NotificationTemplate;
use App\Modules\Notification\Services\Channels\EmailChannel;
use App\Modules\Notification\Services\Channels\InAppChannel;
use App\Modules\Notification\Services\Channels\WhatsAppChannel;
use App\Modules\Notification\Services\Contracts\NotificationChannel;

class NotificationService
{
    /** @var array<string, NotificationChannel> */
    private array $channels;

    public function __construct(
        InAppChannel $inAppChannel,
        EmailChannel $emailChannel,
        WhatsAppChannel $whatsAppChannel,
    ) {
        $this->channels = [
            'in_app' => $inAppChannel,
            'email' => $emailChannel,
            'whatsapp' => $whatsAppChannel,
        ];
    }

    public function send(int $userId, string $type, string $title, string $message, array $channels = ['in_app'], array $data = []): void
    {
        foreach ($channels as $channel) {
            $instance = $this->channels[$channel] ?? null;
            if ($instance instanceof NotificationChannel) {
                $instance->send($userId, $title, $message, array_merge($data, ['type' => $type]));
            }
        }
    }

    public function sendFromTemplate(int $userId, string $templateCode, array $variables = [], array $channels = ['in_app']): void
    {
        $template = NotificationTemplate::query()
            ->where('code', $templateCode)
            ->where('is_active', true)
            ->first();

        if (!$template) {
            return;
        }

        $title = $template->subject ?? $template->name;
        $content = $this->parseTemplate($template->content, $variables);

        $this->send($userId, $templateCode, $title, $content, $channels);
    }

    private function parseTemplate(string $content, array $variables): string
    {
        foreach ($variables as $key => $value) {
            $content = str_replace("{{{$key}}}", (string) $value, $content);
        }

        return $content;
    }
}
