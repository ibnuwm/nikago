<?php

declare(strict_types=1);

namespace App\Modules\Invitation\Actions;

use App\Core\Base\Action;
use App\Modules\Invitation\Models\InvitationTemplate;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class GetPremiumTemplatesAction extends Action
{
    private const SORT_ALLOWED = ['name', 'sort_order', 'favorites_count', 'created_at'];

    public function execute(mixed ...$params): LengthAwarePaginator
    {
        /** @var Request $request */
        $request = $params[0];

        return InvitationTemplate::query()
            ->active()
            ->premium()
            ->search($request->query('search'))
            ->orderBy(
                $this->getSortField($request, self::SORT_ALLOWED, 'sort_order'),
                $this->getSortDirection($request)
            )
            ->paginate($request->query('per_page', 15));
    }
}
