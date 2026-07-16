<?php

declare(strict_types=1);

namespace App\Modules\Budget\Actions;

use App\Core\Base\Action;
use App\Modules\Budget\Models\Budget;
use Illuminate\Http\Request;

class DeleteBudgetAction extends Action
{
    public function execute(mixed ...$params): bool
    {
        $request = $params[0];
        $budgetId = $params[1];
        $user = $request->user();

        $budget = Budget::query()
            ->forUser($user->id)
            ->find($budgetId);

        if (! $budget) {
            return false;
        }

        $budget->delete();

        return true;
    }
}
