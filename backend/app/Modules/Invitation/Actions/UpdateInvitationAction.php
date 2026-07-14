<?php

declare(strict_types=1);

namespace App\Modules\Invitation\Actions;

use App\Core\Base\Action;
use App\Modules\Invitation\Models\Invitation;
use Illuminate\Http\Request;

class UpdateInvitationAction extends Action
{
    public function execute(mixed ...$params): ?Invitation
    {
        /** @var Request $request */
        $request = $params[0];
        /** @var string $uuid */
        $uuid = $params[1];

        $user = $request->user();

        /** @var Invitation|null $invitation */
        $invitation = Invitation::query()
            ->forUser($user->id)
            ->where('uuid', $uuid)
            ->first();

        if (! $invitation) {
            return null;
        }

        $invitation->update([
            'title' => $request->input('title', $invitation->title),
            'slug' => $request->input('slug', $invitation->slug),
            'cover_image' => $request->input('cover_image', $invitation->cover_image),
            'description' => $request->input('description', $invitation->description),
        ]);

        return $invitation->fresh();
    }
}
