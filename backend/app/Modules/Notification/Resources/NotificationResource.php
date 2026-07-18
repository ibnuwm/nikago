<?php

declare(strict_types=1);

namespace App\Modules\Notification\Resources;

use App\Core\Base\Resource;
use Carbon\Carbon;

class NotificationResource extends Resource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->uuid,
            'type' => $this->resource->type,
            'title' => $this->resource->title,
            'message' => $this->resource->message,
            'channel' => $this->resource->channel,
            'is_read' => $this->resource->is_read,
            'read_at' => $this->resource->read_at instanceof Carbon
                ? $this->resource->read_at->toIsoString()
                : null,
            'data' => $this->resource->data,
            'created_at' => $this->resource->created_at instanceof Carbon
                ? $this->resource->created_at->toIsoString()
                : null,
        ];
    }
}
