<?php

declare(strict_types=1);

namespace App\Modules\System\Actions;

use App\Core\Base\Action;
use App\Core\Exceptions\ValidationException;
use App\Modules\Authentication\Models\User;
use App\Modules\Authentication\Resources\UserResource;
use Illuminate\Http\Request;

class UpdateProfileAction extends Action
{
    public function execute(mixed ...$params): array
    {
        /** @var Request $request */
        $request = $params[0];
        $data = $params[1];

        /** @var User $user */
        $user = $request->user();

        if (isset($data['email']) && $data['email'] !== $user->email) {
            $emailTaken = User::where('email', $data['email'])
                ->where('id', '!=', $user->id)
                ->exists();

            if ($emailTaken) {
                throw new ValidationException([
                    'email' => ['The email has already been taken.'],
                ]);
            }
        }

        $user->update($data);

        return [
            'user' => new UserResource($user->fresh()),
        ];
    }
}
