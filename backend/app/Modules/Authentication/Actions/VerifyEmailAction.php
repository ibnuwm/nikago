<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Actions;

use App\Core\Base\Action;
use App\Core\Exceptions\ValidationException;
use App\Modules\Authentication\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class VerifyEmailAction extends Action
{
    public function execute(mixed ...$params): mixed
    {
        /** @var Request $request */
        $request = $params[0];

        /** @var User $user */
        $user = User::findOrFail($request->route('id'));

        if ($user->hasVerifiedEmail()) {
            throw new ValidationException([
                'email' => ['Email is already verified.'],
            ]);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return true;
    }
}
