<?php

declare(strict_types=1);

namespace App\Modules\Invitation\Actions;

use App\Core\Base\Action;
use App\Modules\Invitation\Models\InvitationTemplate;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class GetTemplatesAction extends Action
{
    private const SORT_ALLOWED = ['name', 'sort_order', 'favorites_count', 'created_at'];

    public function execute(mixed ...$params): LengthAwarePaginator
    {
        /** @var Request $request */
        $request = $params[0];

        return InvitationTemplate::query()
            ->active()
            ->search($request->query('search'))
            ->category($request->query('category'))
            ->when($request->query('premium') === 'true', function ($query): void {
                $query->premium();
            })
            ->orderBy(
                $this->getSortField($request, self::SORT_ALLOWED, 'sort_order'),
                $this->getSortDirection($request)
            )
            ->paginate($request->query('per_page', 15));
    }
}
