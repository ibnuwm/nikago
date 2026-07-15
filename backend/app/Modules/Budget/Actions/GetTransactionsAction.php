<?php

declare(strict_types=1);

namespace App\Modules\Budget\Actions;

use App\Modules\Budget\Models\BudgetCategory;
use App\Modules\Budget\Models\BudgetTransaction;
use App\Modules\Budget\Resources\BudgetTransactionResource;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class GetTransactionsAction
{
    public function execute(Authenticatable $user, int $budgetId, ?int $categoryId = null, array $params = []): AnonymousResourceCollection
    {
        $perPage = min((int) ($params['per_page'] ?? 15), 100);
        $type = $params['type'] ?? null;
        $fromDate = $params['from_date'] ?? null;
        $toDate = $params['to_date'] ?? null;

        $query = BudgetTransaction::query()
            ->whereIn('category_id', function ($q) use ($budgetId): void {
                $q->select('id')
                    ->from('budget_categories')
                    ->where('budget_id', $budgetId);
            })
            ->forUser($user->id);

        if ($categoryId !== null) {
            $query->where('category_id', $categoryId);
        }

        if ($type !== null) {
            $query->where('type', $type);
        }

        if ($fromDate !== null) {
            $query->whereDate('transaction_date', '>=', $fromDate);
        }

        if ($toDate !== null) {
            $query->whereDate('transaction_date', '<=', $toDate);
        }

        /** @var LengthAwarePaginator $paginator */
        $paginator = $query->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return BudgetTransactionResource::collection($paginator);
    }
}
