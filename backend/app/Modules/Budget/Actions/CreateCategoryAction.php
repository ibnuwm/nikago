<?php

declare(strict_types=1);

namespace App\Modules\Budget\Actions;

use App\Modules\Budget\Models\Budget;
use App\Modules\Budget\Models\BudgetCategory;
use App\Modules\Budget\Requests\StoreCategoryRequest;
use App\Modules\Budget\Resources\BudgetCategoryResource;
use Illuminate\Contracts\Auth\Authenticatable;

class CreateCategoryAction
{
    public function execute(StoreCategoryRequest $request, Authenticatable $user, int $budgetId): BudgetCategoryResource
    {
        $budget = Budget::query()->forUser($user->id)->findOrFail($budgetId);

        $maxOrder = (int) BudgetCategory::query()->where('budget_id', $budget->id)->max('sort_order');
        $sortOrder = $request->input('sort_order', $maxOrder + 1);

        $category = BudgetCategory::query()->create([
            'budget_id' => $budget->id,
            'name' => $request->input('name'),
            'allocated_amount' => $request->input('allocated_amount', 0),
            'sort_order' => $sortOrder,
        ]);

        return new BudgetCategoryResource($category->load('transactions'));
    }
}
