<?php

declare(strict_types=1);

namespace App\Modules\Invitation\Actions;

use App\Core\Base\Action;
use App\Modules\Invitation\Models\Invitation;
use App\Modules\Wedding\Models\Wedding;
use Illuminate\Http\Request;

class CreateInvitationAction extends Action
{
    public function execute(mixed ...$params): ?Invitation
    {
        /** @var Request $request */
        $request = $params[0];

        $user = $request->user();

        /** @var Wedding|null $wedding */
        $wedding = Wedding::query()
            ->forUser($user->id)
            ->where('uuid', $request->input('wedding_id'))
            ->first();

        if (! $wedding) {
            return null;
        }

        return Invitation::create([
            'tenant_id' => $user->tenant_id ?? 1,
            'wedding_id' => $wedding->id,
            'template_id' => 1,
            'title' => $request->input('title'),
            'slug' => $request->input('slug'),
            'cover_image' => $request->input('cover_image'),
            'description' => $request->input('description'),
        ]);
    }
}
