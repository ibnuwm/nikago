<?php

declare(strict_types=1);

namespace App\Modules\Notification\Actions;

use App\Modules\Notification\Models\NotificationTemplate;
use App\Modules\Notification\Resources\NotificationTemplateResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ListTemplatesAction
{
    public function execute(array $params = []): AnonymousResourceCollection
    {
        $perPage = (int) ($params['per_page'] ?? 15);

        $query = NotificationTemplate::query()
            ->orderBy('name');

        if (isset($params['is_active'])) {
            $query->where('is_active', filter_var($params['is_active'], FILTER_VALIDATE_BOOLEAN));
        }

        if (!empty($params['channel'])) {
            $query->where('channel', $params['channel']);
        }

        return NotificationTemplateResource::collection($query->paginate($perPage));
    }
}
