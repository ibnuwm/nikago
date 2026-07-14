<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Actions;

use App\Core\Base\Action;
use App\Core\Exceptions\ValidationException;
use App\Modules\Authentication\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordAction extends Action
{
    public function execute(mixed ...$params): mixed
    {
        /** @var Request $request */
        $request = $params[0];
        $data = $params[1];

        /** @var User $user */
        $user = $request->user();

        if (! Hash::check($data['current_password'], (string) $user->password)) {
            throw new ValidationException([
                'current_password' => ['The provided password is incorrect.'],
            ]);
        }

        $user->forceFill([
            'password' => Hash::make($data['password']),
        ])->save();

        return true;
    }
}
