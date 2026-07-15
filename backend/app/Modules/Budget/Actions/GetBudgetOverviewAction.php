<?php

declare(strict_types=1);

namespace App\Modules\Budget\Actions;

use App\Modules\Budget\Models\Budget;
use App\Modules\Budget\Resources\BudgetSummaryResource;
use App\Modules\Budget\Services\BudgetCalculationService;
use Illuminate\Contracts\Auth\Authenticatable;

class GetBudgetOverviewAction
{
    public function __construct(
        private readonly BudgetCalculationService $calculationService,
    ) {}

    public function execute(Authenticatable $user, int $weddingId): BudgetSummaryResource
    {
        $budgets = Budget::query()
            ->with('categories.transactions')
            ->forUser($user->id)
            ->forWedding($weddingId)
            ->get();

        return new BudgetSummaryResource(
            $this->calculationService->calculateOverview($budgets)
        );
    }
}
