<?php

declare(strict_types=1);

namespace App\Modules\Notification\Actions;

use App\Modules\Notification\Models\NotificationTemplate;
use App\Modules\Notification\Resources\NotificationTemplateResource;

class UpdateTemplateAction
{
    public function execute(string $uuid, array $data): NotificationTemplateResource
    {
        $template = NotificationTemplate::query()
            ->where('uuid', $uuid)
            ->firstOrFail();

        $template->update($data);

        return new NotificationTemplateResource($template->fresh());
    }
}
