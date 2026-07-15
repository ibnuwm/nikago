<?php

declare(strict_types=1);

namespace App\Modules\Guest\Actions;

use App\Core\Base\Action;
use App\Modules\Guest\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class GetCheckInHistoryAction extends Action
{
    public function execute(mixed ...$params): LengthAwarePaginator
    {
        $request = $params[0];

        $user = $request->user();

        return Guest::query()
            ->forUser($user->id)
            ->whereNotNull('invitation_sent_at')
            ->orderBy('invitation_sent_at', 'desc')
            ->paginate($request->query('per_page', 15));
    }
}
