<?php

declare(strict_types=1);

namespace App\Modules\Notification\Actions;

use App\Modules\Notification\Models\NotificationTemplate;

class DeleteTemplateAction
{
    public function execute(string $uuid): void
    {
        $template = NotificationTemplate::query()
            ->where('uuid', $uuid)
            ->firstOrFail();

        $template->delete();
    }
}
