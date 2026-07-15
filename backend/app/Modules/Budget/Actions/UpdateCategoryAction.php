<?php

declare(strict_types=1);

namespace App\Modules\Budget\Actions;

use App\Modules\Budget\Models\Budget;
use App\Modules\Budget\Models\BudgetCategory;
use App\Modules\Budget\Requests\UpdateCategoryRequest;
use App\Modules\Budget\Resources\BudgetCategoryResource;
use Illuminate\Contracts\Auth\Authenticatable;

class UpdateCategoryAction
{
    public function execute(UpdateCategoryRequest $request, Authenticatable $user, int $budgetId, int $categoryId): BudgetCategoryResource
    {
        Budget::query()->forUser($user->id)->findOrFail($budgetId);

        $category = BudgetCategory::query()->where('budget_id', $budgetId)->findOrFail($categoryId);
        $category->update($request->validated());

        return new BudgetCategoryResource($category->fresh()->load('transactions'));
    }
}
