<?php

declare(strict_types=1);

namespace App\Modules\Budget\Actions;

use App\Modules\Budget\Models\Budget;
use App\Modules\Budget\Models\BudgetCategory;
use App\Modules\Budget\Resources\BudgetResource;
use Illuminate\Contracts\Auth\Authenticatable;

class ReorderCategoriesAction
{
    /**
     * @param  array<int, int>  $order
     */
    public function execute(Authenticatable $user, int $budgetId, array $order): BudgetResource
    {
        $budget = Budget::query()
            ->with('categories.transactions')
            ->forUser($user->id)
            ->findOrFail($budgetId);

        $categoryIds = $budget->categories->pluck('id')->toArray();

        foreach ($order as $index => $categoryId) {
            if (in_array((int) $categoryId, $categoryIds, true)) {
                BudgetCategory::query()
                    ->where('id', (int) $categoryId)
                    ->update(['sort_order' => $index]);
            }
        }

        return new BudgetResource($budget->fresh()->load('categories.transactions'));
    }
}
