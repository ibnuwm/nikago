<?php

declare(strict_types=1);

namespace App\Modules\Dashboard\Actions;

use App\Core\Base\Action;

class GetUpcomingEventsAction extends Action
{
    public function execute(mixed ...$params): array
    {
        return [
            'wedding_date' => null,
            'days_remaining' => null,
            'timeline_events' => [],
            'reminders' => [],
        ];
    }
}
