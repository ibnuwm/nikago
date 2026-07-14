<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Actions;

use App\Core\Base\Action;
use Illuminate\Http\Request;

class GetUserAction extends Action
{
    public function execute(mixed ...$params): mixed
    {
        /** @var Request $request */
        $request = $params[0];

        return $request->user();
    }
}
