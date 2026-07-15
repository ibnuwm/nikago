<?php

declare(strict_types=1);

namespace App\Modules\Budget\Actions;

use App\Modules\Budget\Models\Budget;
use App\Modules\Budget\Requests\UpdateBudgetRequest;
use App\Modules\Budget\Resources\BudgetResource;
use Illuminate\Contracts\Auth\Authenticatable;

class UpdateBudgetAction
{
    public function execute(UpdateBudgetRequest $request, Authenticatable $user, int $budgetId): BudgetResource
    {
        $budget = Budget::query()->forUser($user->id)->findOrFail($budgetId);

        $budget->update($request->validated());

        return new BudgetResource($budget->fresh());
    }
}
