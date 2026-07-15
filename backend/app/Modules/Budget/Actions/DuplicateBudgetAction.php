<?php

declare(strict_types=1);

namespace App\Modules\Budget\Actions;

use App\Modules\Budget\Models\Budget;
use App\Modules\Budget\Models\BudgetCategory;
use App\Modules\Budget\Models\BudgetTransaction;
use App\Modules\Budget\Resources\BudgetResource;
use Illuminate\Contracts\Auth\Authenticatable;

class DuplicateBudgetAction
{
    public function execute(Authenticatable $user, int $budgetId): BudgetResource
    {
        $original = Budget::query()
            ->with('categories.transactions')
            ->forUser($user->id)
            ->findOrFail($budgetId);

        $copy = Budget::query()->create([
            'tenant_id' => $original->tenant_id,
            'wedding_id' => $original->wedding_id,
            'title' => $original->title . ' (Copy)',
            'description' => $original->description,
            'total_budget' => $original->total_budget,
        ]);

        foreach ($original->categories as $category) {
            $newCategory = BudgetCategory::query()->create([
                'budget_id' => $copy->id,
                'name' => $category->name,
                'allocated_amount' => $category->allocated_amount,
                'sort_order' => $category->sort_order,
            ]);

            foreach ($category->transactions as $transaction) {
                BudgetTransaction::query()->create([
                    'category_id' => $newCategory->id,
                    'type' => $transaction->type,
                    'amount' => $transaction->amount,
                    'description' => $transaction->description,
                    'transaction_date' => $transaction->transaction_date,
                ]);
            }
        }

        return new BudgetResource($copy->load('categories.transactions'));
    }
}
