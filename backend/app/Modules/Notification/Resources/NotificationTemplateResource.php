<?php

declare(strict_types=1);

namespace App\Modules\Notification\Resources;

use App\Core\Base\Resource;

class NotificationTemplateResource extends Resource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->uuid,
            'code' => $this->resource->code,
            'name' => $this->resource->name,
            'channel' => $this->resource->channel,
            'subject' => $this->resource->subject,
            'content' => $this->resource->content,
            'variables' => $this->resource->variables,
            'is_active' => $this->resource->is_active,
            'created_at' => $this->resource->created_at?->toIsoString(),
        ];
    }
}
