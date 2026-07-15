<?php

declare(strict_types=1);

namespace App\Modules\Budget\Actions;

use App\Modules\Budget\Models\Budget;
use App\Modules\Budget\Models\BudgetCategory;
use App\Modules\Budget\Models\BudgetTransaction;
use App\Modules\Budget\Requests\StoreTransactionRequest;
use App\Modules\Budget\Resources\BudgetTransactionResource;
use Illuminate\Contracts\Auth\Authenticatable;

class CreateTransactionAction
{
    public function execute(StoreTransactionRequest $request, Authenticatable $user, int $budgetId): BudgetTransactionResource
    {
        Budget::query()->forUser($user->id)->findOrFail($budgetId);

        $category = BudgetCategory::query()->where('budget_id', $budgetId)->findOrFail(
            (int) $request->input('category_id')
        );

        $transaction = BudgetTransaction::query()->create([
            'category_id' => $category->id,
            'type' => $request->input('type'),
            'amount' => $request->input('amount'),
            'description' => $request->input('description'),
            'transaction_date' => $request->input('transaction_date'),
        ]);

        return new BudgetTransactionResource($transaction);
    }
}
