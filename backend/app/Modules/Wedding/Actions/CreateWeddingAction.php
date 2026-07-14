<?php

declare(strict_types=1);

namespace App\Modules\Wedding\Actions;

use App\Core\Base\Action;
use App\Modules\Wedding\Models\Wedding;
use Illuminate\Http\Request;

class CreateWeddingAction extends Action
{
    public function execute(mixed ...$params): Wedding
    {
        /** @var Request $request */
        $request = $params[0];

        $user = $request->user();

        return Wedding::create([
            'tenant_id' => $user->tenant_id ?? 1,
            'user_id' => $user->id,
            'title' => $request->input('title'),
            'theme' => $request->input('theme'),
            'cover_image' => $request->input('cover_image'),
        ]);
    }
}
