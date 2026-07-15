<?php

declare(strict_types=1);

namespace App\Modules\Budget\Actions;

use App\Modules\Budget\Models\Budget;
use App\Modules\Budget\Resources\BudgetSummaryResource;
use Illuminate\Contracts\Auth\Authenticatable;

class GetBudgetSummaryAction
{
    public function execute(Authenticatable $user, int $budgetId): BudgetSummaryResource
    {
        $budget = Budget::query()
            ->with('categories.transactions')
            ->forUser($user->id)
            ->findOrFail($budgetId);

        $totalBudget = (float) $budget->total_budget;
        $totalSpent = 0;
        $categoryBreakdown = [];

        foreach ($budget->categories as $category) {
            $spent = (float) $category->transactions
                ->where('type', 'expense')
                ->sum('amount');
            $totalSpent += $spent;

            $categoryBreakdown[] = [
                'id' => $category->uuid,
                'name' => $category->name,
                'allocated' => (float) $category->allocated_amount,
                'spent' => $spent,
                'remaining' => max(0, (float) $category->allocated_amount - $spent),
                'percentage_used' => $category->allocated_amount > 0
                    ? round(($spent / (float) $category->allocated_amount) * 100, 2)
                    : 0,
            ];
        }

        $totalRemaining = max(0, $totalBudget - $totalSpent);

        return new BudgetSummaryResource([
            'total_budget' => $totalBudget,
            'total_spent' => $totalSpent,
            'total_remaining' => $totalRemaining,
            'percentage_used' => $totalBudget > 0
                ? round(($totalSpent / $totalBudget) * 100, 2)
                : 0,
            'category_breakdown' => $categoryBreakdown,
        ]);
    }
}
