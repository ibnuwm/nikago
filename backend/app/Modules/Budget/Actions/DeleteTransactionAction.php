<?php

declare(strict_types=1);

namespace App\Modules\Budget\Actions;

use App\Modules\Budget\Models\Budget;
use App\Modules\Budget\Models\BudgetTransaction;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;

class DeleteTransactionAction
{
    public function execute(Authenticatable $user, int $budgetId, int $transactionId): JsonResponse
    {
        Budget::query()->forUser($user->id)->findOrFail($budgetId);

        $transaction = BudgetTransaction::query()
            ->whereIn('category_id', function ($q) use ($budgetId): void {
                $q->select('id')
                    ->from('budget_categories')
                    ->where('budget_id', $budgetId);
            })
            ->findOrFail($transactionId);

        $transaction->delete();

        return response()->json(['success' => true]);
    }
}
