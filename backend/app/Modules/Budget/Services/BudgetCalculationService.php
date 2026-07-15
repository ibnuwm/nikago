<?php

declare(strict_types=1);

namespace App\Modules\Budget\Services;

use App\Modules\Budget\Models\Budget;
use Illuminate\Database\Eloquent\Collection;

class BudgetCalculationService
{
    public function calculate(Budget $budget): array
    {
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

        return [
            'total_budget' => $totalBudget,
            'total_spent' => $totalSpent,
            'total_remaining' => max(0, $totalBudget - $totalSpent),
            'percentage_used' => $totalBudget > 0
                ? round(($totalSpent / $totalBudget) * 100, 2)
                : 0,
            'category_breakdown' => $categoryBreakdown,
        ];
    }

    public function calculateOverview(Collection $budgets): array
    {
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

        return [
            'total_budget' => $totalBudget,
            'total_spent' => $totalSpent,
            'total_remaining' => max(0, $totalBudget - $totalSpent),
            'percentage_used' => $totalBudget > 0
                ? round(($totalSpent / $totalBudget) * 100, 2)
                : 0,
            'budget_count' => $budgets->count(),
        ];
    }
}
