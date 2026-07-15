<?php

declare(strict_types=1);

namespace App\Modules\Guest\Actions;

use App\Core\Base\Action;
use App\Modules\Guest\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class GetGuestsAction extends Action
{
    private const SORT_ALLOWED = ['created_at', 'name', 'status', 'pax', 'updated_at'];

    public function execute(mixed ...$params): LengthAwarePaginator
    {
        $request = $params[0];

        $user = $request->user();

        return Guest::query()
            ->forUser($user->id)
            ->with('rsvp')
            ->search($request->query('search'))
            ->when($request->query('status'), function ($query, $status): void {
                $query->where('status', $status);
            })
            ->when($request->query('wedding_id'), function ($query, $weddingId): void {
                $query->forWedding((int) $weddingId);
            })
            ->when($request->query('category_id'), function ($query, $categoryId): void {
                $query->where('category_id', $categoryId);
            })
            ->when($request->query('group_id'), function ($query, $groupId): void {
                $query->where('group_id', $groupId);
            })
            ->orderBy(
                $this->getSortField($request, self::SORT_ALLOWED, 'created_at'),
                $this->getSortDirection($request, 'desc')
            )
            ->paginate($request->query('per_page', 15));
    }
}
