<?php

declare(strict_types=1);

namespace App\Modules\Budget\Actions;

use App\Core\Base\Action;
use App\Modules\Budget\Models\Budget;
use App\Modules\Budget\Models\BudgetTransaction;
use Illuminate\Http\Request;

class DeleteTransactionAction extends Action
{
    public function execute(mixed ...$params): bool
    {
        $request = $params[0];
        $budgetId = $params[1];
        $transactionId = $params[2];
        $user = $request->user();

        $budget = Budget::query()
            ->forUser($user->id)
            ->find($budgetId);

        if (! $budget) {
            return false;
        }

        $transaction = BudgetTransaction::query()
            ->whereIn('category_id', function ($q) use ($budgetId): void {
                $q->select('id')
                    ->from('budget_categories')
                    ->where('budget_id', $budgetId);
            })
            ->find($transactionId);

        if (! $transaction) {
            return false;
        }

        $transaction->delete();

        return true;
    }
}
