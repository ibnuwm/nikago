<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Actions;

use App\Core\Base\Action;
use App\Core\Exceptions\ValidationException;
use App\Modules\Authentication\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\NewAccessToken;

class LoginAction extends Action
{
    public function execute(mixed ...$params): array
    {
        $data = $params[0];

        if (! Auth::attempt($this->credentials($data), $data['remember'] ?? false)) {
            throw new ValidationException([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        /** @var User $user */
        $user = Auth::user();

        if (! $user->isActive()) {
            Auth::logout();

            throw new ValidationException([
                'email' => ['Your account is not active.'],
            ]);
        }

        $user->markAsLoggedIn();

        /** @var NewAccessToken $token */
        $token = $user->createToken('auth-token');

        return [
            'user' => $user,
            'token' => $token->plainTextToken,
        ];
    }

    /**
     * @param  array{email: string, password: string}  $data
     *
     * @return array{email: string, password: string}
     */
    private function credentials(array $data): array
    {
        return [
            'email' => $data['email'],
            'password' => $data['password'],
        ];
    }
}
