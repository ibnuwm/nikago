<?php

declare(strict_types=1);

namespace App\Modules\Budget\Actions;

use App\Modules\Budget\Models\Budget;
use App\Modules\Budget\Requests\StoreBudgetRequest;
use App\Modules\Budget\Resources\BudgetResource;
use App\Modules\Wedding\Models\Wedding;
use Illuminate\Contracts\Auth\Authenticatable;

class CreateBudgetAction
{
    public function execute(StoreBudgetRequest $request, Authenticatable $user): BudgetResource
    {
        $wedding = Wedding::query()->forUser($user->id)->findOrFail(
            (int) $request->input('wedding_id')
        );

        $budget = Budget::query()->create([
            'tenant_id' => $wedding->tenant_id,
            'wedding_id' => $wedding->id,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'total_budget' => $request->input('total_budget', 0),
        ]);

        return new BudgetResource($budget);
    }
}
