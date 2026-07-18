<?php

declare(strict_types=1);

namespace App\Modules\Notification\Actions;

use App\Modules\Notification\Models\NotificationTemplate;
use App\Modules\Notification\Resources\NotificationTemplateResource;

class CreateTemplateAction
{
    public function execute(array $data): NotificationTemplateResource
    {
        $template = NotificationTemplate::query()->create($data);

        return new NotificationTemplateResource($template);
    }
}
