<?php

declare(strict_types=1);

namespace App\Modules\Wedding\Actions;

use App\Core\Base\Action;
use App\Modules\Wedding\Models\Wedding;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class GetWeddingsAction extends Action
{
    private const SORT_ALLOWED = ['created_at', 'title', 'status', 'updated_at'];

    public function execute(mixed ...$params): LengthAwarePaginator
    {
        /** @var Request $request */
        $request = $params[0];

        $user = $request->user();

        return Wedding::query()
            ->forUser($user->id)
            ->search($request->query('search'))
            ->when($request->query('status'), function ($query, $status): void {
                $query->status($status);
            })
            ->orderBy(
                $this->getSortField($request, self::SORT_ALLOWED, 'created_at'),
                $this->getSortDirection($request, 'desc')
            )
            ->paginate($request->query('per_page', 15));
    }
}
