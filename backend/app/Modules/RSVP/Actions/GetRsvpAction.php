<?php

declare(strict_types=1);

namespace App\Modules\RSVP\Actions;

use App\Core\Base\Action;
use App\Modules\RSVP\Models\Rsvp;
use Illuminate\Http\Request;

class GetRsvpAction extends Action
{
    public function execute(mixed ...$params): ?Rsvp
    {
        /** @var Request $request */
        $request = $params[0];
        /** @var string $uuid */
        $uuid = $params[1];

        $user = $request->user();

        return Rsvp::query()
            ->where('uuid', $uuid)
            ->where('tenant_id', $user->tenant_id)
            ->with('guest')
            ->first();
    }
}
