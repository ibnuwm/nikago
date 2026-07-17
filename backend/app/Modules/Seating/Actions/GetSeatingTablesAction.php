<?php

declare(strict_types=1);

namespace App\Modules\Seating\Actions;

use App\Core\Base\Action;
use App\Modules\Seating\Models\SeatingTable;
use Illuminate\Pagination\LengthAwarePaginator;

class GetSeatingTablesAction extends Action
{
    private const SORT_ALLOWED = ['created_at', 'name', 'capacity', 'sort_order', 'updated_at'];

    public function execute(mixed ...$params): LengthAwarePaginator
    {
        $request = $params[0];
        $user = $request->user();

        return SeatingTable::query()
            ->forUser($user->id)
            ->withCount('assignments')
            ->when($request->query('wedding_id'), function ($query, $weddingId): void {
                $query->forWedding((int) $weddingId);
            })
            ->orderBy(
                $this->getSortField($request, self::SORT_ALLOWED, 'sort_order'),
                $this->getSortDirection($request)
            )
            ->paginate($request->query('per_page', 15));
    }
}
