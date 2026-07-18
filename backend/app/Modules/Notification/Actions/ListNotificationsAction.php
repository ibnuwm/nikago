<?php

declare(strict_types=1);

namespace App\Modules\Notification\Actions;

use App\Modules\Notification\Models\Notification;
use App\Modules\Notification\Resources\NotificationResource;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ListNotificationsAction
{
    public function execute(Authenticatable $user, array $params = []): AnonymousResourceCollection
    {
        $perPage = (int) ($params['per_page'] ?? 15);

        $query = Notification::query()
            ->forUser($user->id)
            ->orderByDesc('created_at');

        if (isset($params['is_read'])) {
            $query->where('is_read', filter_var($params['is_read'], FILTER_VALIDATE_BOOLEAN));
        }

        if (!empty($params['type'])) {
            $query->where('type', $params['type']);
        }

        return NotificationResource::collection($query->paginate($perPage));
    }
}
