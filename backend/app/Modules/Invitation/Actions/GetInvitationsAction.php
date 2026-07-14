<?php

declare(strict_types=1);

namespace App\Modules\Invitation\Actions;

use App\Core\Base\Action;
use App\Modules\Invitation\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class GetInvitationsAction extends Action
{
    public function execute(mixed ...$params): LengthAwarePaginator
    {
        /** @var Request $request */
        $request = $params[0];

        $user = $request->user();

        return Invitation::query()
            ->forUser($user->id)
            ->search($request->query('search'))
            ->when($request->query('status'), function ($query, $status): void {
                $query->status($status);
            })
            ->orderBy($this->getSortField($request), $this->getSortDirection($request))
            ->paginate($request->query('per_page', 15));
    }

    private function getSortField(Request $request): string
    {
        $sort = $request->query('sort', 'created_at');

        $allowed = ['created_at', 'title', 'status', 'updated_at'];

        return in_array($sort, $allowed) ? $sort : 'created_at';
    }

    private function getSortDirection(Request $request): string
    {
        $direction = $request->query('direction', 'desc');

        return in_array($direction, ['asc', 'desc']) ? $direction : 'desc';
    }
}
