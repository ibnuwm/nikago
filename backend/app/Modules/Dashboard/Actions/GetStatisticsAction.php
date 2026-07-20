<?php

declare(strict_types=1);

namespace App\Modules\Dashboard\Actions;

use App\Core\Base\Action;
use App\Modules\Budget\Models\Budget;
use App\Modules\Budget\Models\BudgetTransaction;
use App\Modules\Guest\Models\Guest;
use App\Modules\Invitation\Models\Invitation;
use App\Modules\RSVP\Models\Rsvp;
use App\Modules\Wedding\Models\Wedding;

class GetStatisticsAction extends Action
{
    public function execute(mixed ...$params): array
    {
        /** @var Wedding|null $wedding */
        $wedding = $params[0] ?? null;

        if ($wedding === null) {
            return [
                'invitations_count' => 0,
                'guests_count' => 0,
                'rsvp_pending_count' => 0,
                'rsvp_confirmed_count' => 0,
                'rsvp_total_guests' => 0,
                'budget_total' => 0,
                'budget_spent' => 0,
                'vendors_count' => 0,
                'checklist_progress' => 0,
            ];
        }

        $weddingId = $wedding->id;

        $guestCount = Guest::where('wedding_id', $weddingId)->count();

        $rsvpPending = Rsvp::whereHas('guest', function ($q) use ($weddingId): void {
            $q->where('wedding_id', $weddingId);
        })->whereNull('attendance')->count();

        $rsvpConfirmed = Rsvp::whereHas('guest', function ($q) use ($weddingId): void {
            $q->where('wedding_id', $weddingId);
        })->where('attendance', Rsvp::ATTENDANCE_YES)->count();

        $rsvpTotalGuests = (int) Rsvp::whereHas('guest', function ($q) use ($weddingId): void {
            $q->where('wedding_id', $weddingId);
        })->where('attendance', Rsvp::ATTENDANCE_YES)->sum('total_guest');

        $budget = Budget::where('wedding_id', $weddingId)->first();
        $budgetTotal = (float) ($budget !== null ? $budget->total_budget : 0);

        $budgetSpent = 0.0;
        if ($budget !== null) {
            $budgetSpent = (float) BudgetTransaction::whereHas('category', function ($q) use ($budget): void {
                $q->where('budget_id', $budget->id);
            })->where('type', BudgetTransaction::TYPE_EXPENSE)->sum('amount');
        }

        $vendorCount = \App\Modules\Booking\Models\Booking::where('wedding_id', $weddingId)
            ->distinct('vendor_id')
            ->count('vendor_id');

        return [
            'invitations_count' => Invitation::where('wedding_id', $weddingId)->count(),
            'guests_count' => $guestCount,
            'rsvp_pending_count' => $rsvpPending,
            'rsvp_confirmed_count' => $rsvpConfirmed,
            'rsvp_total_guests' => $rsvpTotalGuests,
            'budget_total' => $budgetTotal,
            'budget_spent' => $budgetSpent,
            'vendors_count' => $vendorCount,
            'checklist_progress' => $this->getChecklistProgress($weddingId),
        ];
    }

    private function getChecklistProgress(int $weddingId): float
    {
        $totalItems = \App\Modules\Checklist\Models\ChecklistItem::whereHas('checklist', function ($q) use ($weddingId): void {
            $q->where('wedding_id', $weddingId);
        })->count();

        if ($totalItems === 0) {
            return 0.0;
        }

        $completedItems = \App\Modules\Checklist\Models\ChecklistItem::whereHas('checklist', function ($q) use ($weddingId): void {
            $q->where('wedding_id', $weddingId);
        })->whereNotNull('completed_at')->count();

        return round(($completedItems / $totalItems) * 100, 1);
    }
}
