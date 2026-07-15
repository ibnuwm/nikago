<?php

declare(strict_types=1);

namespace App\Modules\Budget\Actions;

use App\Modules\Budget\Models\Budget;
use App\Modules\Budget\Models\BudgetCategory;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;

class DeleteCategoryAction
{
    public function execute(Authenticatable $user, int $budgetId, int $categoryId): JsonResponse
    {
        Budget::query()->forUser($user->id)->findOrFail($budgetId);

        $category = BudgetCategory::query()->where('budget_id', $budgetId)->findOrFail($categoryId);
        $category->delete();

        return response()->json(['success' => true]);
    }
}
