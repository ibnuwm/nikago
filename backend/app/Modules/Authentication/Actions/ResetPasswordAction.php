<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Actions;

use App\Core\Base\Action;
use App\Core\Exceptions\ValidationException;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordAction extends Action
{
    public function execute(mixed ...$params): string
    {
        $data = $params[0];

        $status = Password::reset(
            $data,
            function ($user, string $password) use ($data): void {
                $user->forceFill([
                    'password' => $data['password'],
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw new ValidationException([
                'email' => [__($status)],
            ]);
        }

        return __($status);
    }
}
