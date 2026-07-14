<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Actions;

use App\Core\Base\Action;
use App\Core\Exceptions\ValidationException;
use Illuminate\Http\Request;

class ResendVerificationAction extends Action
{
    public function execute(mixed ...$params): mixed
    {
        /** @var Request $request */
        $request = $params[0];

        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            throw new ValidationException([
                'email' => ['Email is already verified.'],
            ]);
        }

        $user->sendEmailVerificationNotification();

        return true;
    }
}
