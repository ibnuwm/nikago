<?php

declare(strict_types=1);

namespace App\Modules\Budget\Actions;

use App\Modules\Budget\Models\Budget;
use App\Modules\Budget\Resources\BudgetSummaryResource;
use Illuminate\Contracts\Auth\Authenticatable;

class GetBudgetOverviewAction
{
    public function execute(Authenticatable $user, int $weddingId): BudgetSummaryResource
    {
        $budgets = Budget::query()
            ->with('categories.transactions')
            ->forUser($user->id)
            ->forWedding($weddingId)
            ->get();

        $totalBudget = 0;
        $totalSpent = 0;

        foreach ($budgets as $budget) {
            $totalBudget += (float) $budget->total_budget;

            foreach ($budget->categories as $category) {
                $totalSpent += (float) $category->transactions
                    ->where('type', 'expense')
                    ->sum('amount');
            }
        }

        $totalRemaining = max(0, $totalBudget - $totalSpent);

        return new BudgetSummaryResource([
            'total_budget' => $totalBudget,
            'total_spent' => $totalSpent,
            'total_remaining' => $totalRemaining,
            'percentage_used' => $totalBudget > 0
                ? round(($totalSpent / $totalBudget) * 100, 2)
                : 0,
            'budget_count' => $budgets->count(),
        ]);
    }
}
