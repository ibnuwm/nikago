<?php

declare(strict_types=1);

namespace App\Modules\RSVP\Actions;

use App\Core\Base\Action;
use App\Modules\RSVP\Models\Rsvp;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class GetRsvpsAction extends Action
{
    private const SORT_ALLOWED = ['created_at', 'attendance', 'total_guest', 'confirmed_at'];

    public function execute(mixed ...$params): LengthAwarePaginator
    {
        /** @var Request $request */
        $request = $params[0];

        $user = $request->user();
        /** @var int $tenantId */
        $tenantId = $user->tenant_id;

        return Rsvp::query()
            ->forTenant($tenantId)
            ->when($request->query('attendance'), function ($query, $attendance): void {
                $query->attendance($attendance);
            })
            ->when($request->query('search'), function ($query, $search): void {
                $query->whereHas('guest', function ($q) use ($search): void {
                    $escaped = str_replace(['#', '%', '_'], ['##', '#%', '#_'], $search);
                    $q->whereRaw('LOWER(name) LIKE ? ESCAPE \'#\'', ['%' . strtolower($escaped) . '%'])
                        ->orWhereRaw('LOWER(phone) LIKE ? ESCAPE \'#\'', ['%' . strtolower($escaped) . '%'])
                        ->orWhereRaw('LOWER(email) LIKE ? ESCAPE \'#\'', ['%' . strtolower($escaped) . '%']);
                });
            })
            ->with('guest')
            ->orderBy(
                $this->getSortField($request, self::SORT_ALLOWED, 'created_at'),
                $this->getSortDirection($request, 'desc')
            )
            ->paginate($request->query('per_page', 15));
    }
}
