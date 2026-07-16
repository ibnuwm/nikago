<?php

declare(strict_types=1);

namespace App\Modules\Timeline\Actions;

use App\Core\Base\Action;
use App\Modules\Timeline\Models\Timeline;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class GetTimelinesAction extends Action
{
    private const SORT_ALLOWED = ['created_at', 'title', 'progress', 'updated_at'];

    public function execute(mixed ...$params): LengthAwarePaginator
    {
        $request = $params[0];
        $user = $request->user();

        return Timeline::query()
            ->forUser($user->id)
            ->withCount('tasks')
            ->when($request->query('wedding_id'), function ($query, $weddingId): void {
                $query->forWedding((int) $weddingId);
            })
            ->orderBy(
                $this->getSortField($request, self::SORT_ALLOWED, 'created_at'),
                $this->getSortDirection($request, 'desc')
            )
            ->paginate($request->query('per_page', 15));
    }
}
