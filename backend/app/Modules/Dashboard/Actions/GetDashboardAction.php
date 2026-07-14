<?php

declare(strict_types=1);

namespace App\Modules\Dashboard\Actions;

use App\Core\Base\Action;
use App\Modules\Authentication\Resources\UserResource;
use Illuminate\Http\Request;

class GetDashboardAction extends Action
{
    public function execute(mixed ...$params): array
    {
        /** @var Request $request */
        $request = $params[0];

        $user = $request->user();

        $upcomingEvents = app(GetUpcomingEventsAction::class)->execute($request);

        return [
            'user' => new UserResource($user),
            'wedding' => null,
            'subscription' => null,
            'statistics' => app(GetStatisticsAction::class)->execute(),
            'reminders' => $upcomingEvents['reminders'],
            'recent_activity' => app(GetRecentActivityAction::class)->execute(),
            'upcoming_events' => $upcomingEvents,
        ];
    }
}
