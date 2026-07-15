<?php

declare(strict_types=1);

namespace App\Modules\Budget\Actions;

use App\Modules\Budget\Models\Budget;
use App\Modules\Budget\Resources\BudgetSummaryResource;
use App\Modules\Budget\Services\BudgetCalculationService;
use Illuminate\Contracts\Auth\Authenticatable;

class RecalculateBudgetAction
{
    public function __construct(
        private readonly BudgetCalculationService $calculationService,
    ) {}

    public function execute(Authenticatable $user, int $budgetId): BudgetSummaryResource
    {
        $budget = Budget::query()
            ->with('categories.transactions')
            ->forUser($user->id)
            ->findOrFail($budgetId);

        return new BudgetSummaryResource(
            $this->calculationService->calculate($budget)
        );
    }
}
