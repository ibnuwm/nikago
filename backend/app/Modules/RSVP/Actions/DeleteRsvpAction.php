<?php

declare(strict_types=1);

namespace App\Modules\RSVP\Actions;

use App\Core\Base\Action;
use App\Modules\RSVP\Models\Rsvp;
use Illuminate\Http\Request;

class DeleteRsvpAction extends Action
{
    public function execute(mixed ...$params): bool
    {
        /** @var Request $request */
        $request = $params[0];
        /** @var string $uuid */
        $uuid = $params[1];

        $user = $request->user();

        $rsvp = Rsvp::query()
            ->where('uuid', $uuid)
            ->where('tenant_id', $user->tenant_id)
            ->first();

        if (! $rsvp) {
            return false;
        }

        $rsvp->delete();

        return true;
    }
}
