<?php

declare(strict_types=1);

namespace App\Modules\Budget\Actions;

use App\Modules\Budget\Models\Budget;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;

class DeleteBudgetAction
{
    public function execute(Authenticatable $user, int $budgetId): JsonResponse
    {
        $budget = Budget::query()->forUser($user->id)->findOrFail($budgetId);

        $budget->delete();

        return response()->json(['success' => true]);
    }
}
