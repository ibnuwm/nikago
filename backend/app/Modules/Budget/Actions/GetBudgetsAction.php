<?php

declare(strict_types=1);

namespace App\Modules\Budget\Actions;

use App\Modules\Budget\Models\Budget;
use App\Modules\Budget\Resources\BudgetResource;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class GetBudgetsAction
{
    public function execute(Authenticatable $user, array $params = []): AnonymousResourceCollection
    {
        $perPage = min((int) ($params['per_page'] ?? 15), 100);
        $weddingId = $params['wedding_id'] ?? null;

        $query = Budget::query()->forUser($user->id);

        if ($weddingId !== null) {
            $query->forWedding((int) $weddingId);
        }

        /** @var LengthAwarePaginator $paginator */
        $paginator = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return BudgetResource::collection($paginator);
    }
}
