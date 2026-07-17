<?php

declare(strict_types=1);

namespace App\Modules\Rundown\Actions;

use App\Core\Base\Action;
use App\Modules\Rundown\Models\Rundown;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class GetRundownsAction extends Action
{
    private const SORT_ALLOWED = ['created_at', 'title', 'status', 'updated_at'];

    public function execute(mixed ...$params): LengthAwarePaginator
    {
        $request = $params[0];
        $user = $request->user();

        return Rundown::query()
            ->forUser($user->id)
            ->withCount('items')
            ->when($request->query('wedding_id'), function ($query, $weddingId): void {
                $query->forWedding((int) $weddingId);
            })
            ->orderBy(
                $this->getSortField($request, self::SORT_ALLOWED, 'created_at'),
                $this->getSortDirection($request)
            )
            ->paginate($request->query('per_page', 15));
    }
}
