<?php

declare(strict_types=1);

namespace App\Modules\Dashboard\Actions;

use App\Core\Base\Action;

class GetStatisticsAction extends Action
{
    public function execute(mixed ...$params): array
    {
        return [
            'invitations_count' => 0,
            'guests_count' => 0,
            'rsvp_pending_count' => 0,
            'rsvp_confirmed_count' => 0,
            'budget_total' => 0,
            'budget_spent' => 0,
            'vendors_count' => 0,
        ];
    }
}
