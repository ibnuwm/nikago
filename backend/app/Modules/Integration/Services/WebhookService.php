<?php

declare(strict_types=1);

namespace App\Modules\Integration\Services;

use App\Modules\Integration\Models\Webhook;
use App\Modules\Integration\Models\WebhookLog;

class WebhookService
{
    public function list(int $userId): array
    {
        return Webhook::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }

    public function create(int $userId, array $data): Webhook
    {
        return Webhook::create([
            'user_id' => $userId,
            'name' => $data['name'],
            'url' => $data['url'],
            'events' => $data['events'] ?? null,
        ]);
    }

    public function delete(int $userId, string $uuid): bool
    {
        $webhook = Webhook::where('uuid', $uuid)
            ->where('user_id', $userId)
            ->first();

        if (! $webhook) {
            return false;
        }

        $webhook->delete();

        return true;
    }

    public function log(Webhook $webhook, array $data): WebhookLog
    {
        return WebhookLog::create([
            'webhook_id' => $webhook->id,
            'event' => $data['event'] ?? null,
            'payload' => $data['payload'] ?? null,
            'response' => $data['response'] ?? null,
            'status_code' => $data['status_code'] ?? null,
            'status' => $data['status'] ?? 'pending',
            'attempt' => $data['attempt'] ?? 1,
        ]);
    }
}
