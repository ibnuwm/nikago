<?php

declare(strict_types=1);

namespace App\Modules\Checklist\Actions;

use App\Core\Base\Action;
use App\Modules\Checklist\Models\Checklist;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class GetChecklistsAction extends Action
{
    private const SORT_ALLOWED = ['created_at', 'title', 'progress', 'updated_at'];

    public function execute(mixed ...$params): LengthAwarePaginator
    {
        $request = $params[0];
        $user = $request->user();

        return Checklist::query()
            ->forUser($user->id)
            ->withCount('items')
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
