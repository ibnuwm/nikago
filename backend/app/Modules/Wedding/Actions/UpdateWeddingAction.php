<?php

declare(strict_types=1);

namespace App\Modules\Wedding\Actions;

use App\Core\Base\Action;
use App\Modules\Wedding\Models\Wedding;
use Illuminate\Http\Request;

class UpdateWeddingAction extends Action
{
    public function execute(mixed ...$params): ?Wedding
    {
        /** @var Request $request */
        $request = $params[0];
        /** @var string $uuid */
        $uuid = $params[1];

        $user = $request->user();

        $wedding = Wedding::query()
            ->forUser($user->id)
            ->where('uuid', $uuid)
            ->first();

        if (! $wedding) {
            return null;
        }

        $wedding->update([
            'title' => $request->input('title', $wedding->title),
            'theme' => $request->input('theme', $wedding->theme),
            'cover_image' => $request->input('cover_image', $wedding->cover_image),
        ]);

        return $wedding->fresh();
    }
}
