<?php

declare(strict_types=1);

namespace App\Modules\Budget\Actions;

use App\Modules\Budget\Models\Budget;
use App\Modules\Budget\Resources\BudgetResource;
use Illuminate\Contracts\Auth\Authenticatable;

class GetBudgetAction
{
    public function execute(Authenticatable $user, int $budgetId): BudgetResource
    {
        $budget = Budget::query()
            ->with(['categories.transactions'])
            ->forUser($user->id)
            ->findOrFail($budgetId);

        return new BudgetResource($budget);
    }
}
