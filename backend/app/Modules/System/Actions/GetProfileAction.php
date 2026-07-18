<?php

declare(strict_types=1);

namespace App\Modules\System\Actions;

use App\Core\Base\Action;
use App\Modules\Authentication\Resources\UserResource;
use Illuminate\Http\Request;

class GetProfileAction extends Action
{
    public function execute(mixed ...$params): array
    {
        /** @var Request $request */
        $request = $params[0];

        return [
            'user' => new UserResource($request->user()),
        ];
    }
}
