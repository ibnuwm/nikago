<?php

declare(strict_types=1);

namespace App\Modules\Dashboard\Actions;

use App\Core\Base\Action;
use App\Modules\Timeline\Models\TimelineTask;
use App\Modules\Wedding\Models\Wedding;
use Carbon\Carbon;

class GetUpcomingEventsAction extends Action
{
    public function execute(mixed ...$params): array
    {
        /** @var Wedding|null $wedding */
        $wedding = $params[0] ?? null;

        if ($wedding === null || $wedding->wedding_date === null) {
            return [
                'wedding_date' => null,
                'days_remaining' => null,
                'hours_remaining' => null,
                'phase' => null,
                'timeline_events' => [],
                'reminders' => [],
            ];
        }

        $now = now();
        $weddingDate = Carbon::parse($wedding->wedding_date);
        $daysRemaining = (int) $now->diffInDays($weddingDate, false);
        $hoursRemaining = (int) $now->diffInHours($weddingDate, false);

        $phase = $this->getPlanningPhase($daysRemaining);

        $timelineEvents = TimelineTask::whereHas('timeline', function ($q) use ($wedding): void {
            $q->where('wedding_id', $wedding->id);
        })
            ->whereNull('completed_at')
            ->where('due_date', '>=', $now->toDateString())
            ->orderBy('due_date')
            ->limit(5)
            ->get()
            ->map(fn (TimelineTask $task): array => [
                'id' => $task->uuid,
                'title' => $task->title,
                'due_date' => $task->due_date instanceof Carbon ? $task->due_date->format('Y-m-d') : null,
                'priority' => $task->priority,
            ])
            ->toArray();

        $reminders = collect();

        if ($daysRemaining <= 30 && $daysRemaining > 0) {
            $reminders->push([
                'id' => 'wedding-countdown',
                'title' => "Your wedding is in {$daysRemaining} days!",
                'date' => $weddingDate->format('Y-m-d'),
                'type' => 'wedding',
            ]);
        }

        return [
            'wedding_date' => $weddingDate->format('Y-m-d'),
            'days_remaining' => max($daysRemaining, 0),
            'hours_remaining' => max($hoursRemaining, 0),
            'phase' => $phase,
            'timeline_events' => $timelineEvents,
            'reminders' => $reminders->toArray(),
        ];
    }

    private function getPlanningPhase(int $daysRemaining): string
    {
        return match (true) {
            $daysRemaining > 365 => 'Just Engaged',
            $daysRemaining > 270 => '12+ Months Out',
            $daysRemaining > 180 => '9-12 Months Out',
            $daysRemaining > 90 => '3-9 Months Out',
            $daysRemaining > 30 => '1-3 Months Out',
            $daysRemaining > 7 => 'Final Month',
            $daysRemaining > 0 => 'Final Week',
            default => 'Wedding Day!',
        };
    }
}
