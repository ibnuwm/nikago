<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Actions;

use App\Core\Base\Action;
use Illuminate\Http\Request;

class LogoutAction extends Action
{
    public function execute(mixed ...$params): mixed
    {
        /** @var Request $request */
        $request = $params[0];

        $request->user()->currentAccessToken()->delete();

        return true;
    }
}
