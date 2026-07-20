<?php

declare(strict_types=1);

namespace App\Modules\Dashboard\Actions;

use App\Core\Base\Action;
use App\Modules\Authentication\Resources\UserResource;
use App\Modules\Wedding\Models\Wedding;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GetDashboardAction extends Action
{
    public function execute(mixed ...$params): array
    {
        /** @var Request $request */
        $request = $params[0];

        $user = $request->user();

        $wedding = Wedding::where('user_id', $user->id)
            ->where('status', '!=', Wedding::STATUS_ARCHIVED)
            ->latest()
            ->first();

        $upcomingEvents = app(GetUpcomingEventsAction::class)->execute($wedding);
        $statistics = app(GetStatisticsAction::class)->execute($wedding);

        return [
            'user' => new UserResource($user),
            'wedding' => $wedding !== null ? array_merge(
                $wedding->only(['id', 'uuid', 'title', 'slug', 'status', 'theme', 'cover_image', 'published_at']),
                ['wedding_date' => Carbon::parse($wedding->wedding_date)->format('Y-m-d')]
            ) : null,
            'subscription' => null,
            'statistics' => $statistics,
            'reminders' => $upcomingEvents['reminders'],
            'recent_activity' => app(GetRecentActivityAction::class)->execute($wedding),
            'upcoming_events' => $upcomingEvents,
        ];
    }
}
