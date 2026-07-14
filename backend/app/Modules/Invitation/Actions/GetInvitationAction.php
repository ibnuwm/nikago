<?php

declare(strict_types=1);

namespace App\Modules\Invitation\Actions;

use App\Core\Base\Action;
use App\Modules\Invitation\Models\Invitation;
use Illuminate\Http\Request;

class GetInvitationAction extends Action
{
    public function execute(mixed ...$params): ?Invitation
    {
        /** @var Request $request */
        $request = $params[0];
        /** @var string $uuid */
        $uuid = $params[1];

        $user = $request->user();

        return Invitation::query()
            ->forUser($user->id)
            ->where('uuid', $uuid)
            ->first();
    }
}
