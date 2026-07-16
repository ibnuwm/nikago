<?php

declare(strict_types=1);

namespace App\Modules\Budget\Actions;

use App\Core\Base\Action;
use App\Modules\Budget\Models\Budget;
use App\Modules\Budget\Models\BudgetCategory;
use Illuminate\Http\Request;

class DeleteCategoryAction extends Action
{
    public function execute(mixed ...$params): bool
    {
        $request = $params[0];
        $budgetId = $params[1];
        $categoryId = $params[2];
        $user = $request->user();

        $budget = Budget::query()
            ->forUser($user->id)
            ->find($budgetId);

        if (! $budget) {
            return false;
        }

        $category = BudgetCategory::query()
            ->where('budget_id', $budgetId)
            ->find($categoryId);

        if (! $category) {
            return false;
        }

        $category->delete();

        return true;
    }
}
