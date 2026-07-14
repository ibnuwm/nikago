<?php

declare(strict_types=1);

namespace App\Modules\Invitation\Actions;

use App\Core\Base\Action;
use App\Modules\Invitation\Models\Invitation;
use Illuminate\Http\Request;

class DuplicateInvitationAction extends Action
{
    public function execute(mixed ...$params): ?Invitation
    {
        /** @var Request $request */
        $request = $params[0];
        /** @var string $uuid */
        $uuid = $params[1];

        $user = $request->user();

        /** @var Invitation|null $original */
        $original = Invitation::query()
            ->forUser($user->id)
            ->where('uuid', $uuid)
            ->first();

        if (! $original) {
            return null;
        }

        return Invitation::create([
            'tenant_id' => $original->tenant_id,
            'wedding_id' => $original->wedding_id,
            'title' => $original->title . ' (Copy)',
            'cover_image' => $original->cover_image,
            'description' => $original->description,
        ]);
    }
}
