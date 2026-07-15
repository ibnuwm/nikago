<?php

declare(strict_types=1);

namespace App\Modules\Budget\Actions;

use App\Modules\Budget\Models\Budget;
use App\Modules\Budget\Models\BudgetTransaction;
use App\Modules\Budget\Requests\UpdateTransactionRequest;
use App\Modules\Budget\Resources\BudgetTransactionResource;
use Illuminate\Contracts\Auth\Authenticatable;

class UpdateTransactionAction
{
    public function execute(UpdateTransactionRequest $request, Authenticatable $user, int $budgetId, int $transactionId): BudgetTransactionResource
    {
        Budget::query()->forUser($user->id)->findOrFail($budgetId);

        $transaction = BudgetTransaction::query()
            ->whereIn('category_id', function ($q) use ($budgetId): void {
                $q->select('id')
                    ->from('budget_categories')
                    ->where('budget_id', $budgetId);
            })
            ->findOrFail($transactionId);

        $transaction->update($request->validated());

        return new BudgetTransactionResource($transaction->fresh());
    }
}
