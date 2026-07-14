<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Actions;

use App\Core\Base\Action;
use App\Modules\Authentication\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\NewAccessToken;

class RegisterAction extends Action
{
    public function execute(mixed ...$params): array
    {
        $data = $params[0];

        return DB::transaction(function () use ($data): array {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'status' => User::STATUS_ACTIVE,
            ]);

            $user->assignRole('guest');

            /** @var NewAccessToken $token */
            $token = $user->createToken('auth-token');

            return [
                'user' => $user,
                'token' => $token->plainTextToken,
            ];
        });
    }
}
