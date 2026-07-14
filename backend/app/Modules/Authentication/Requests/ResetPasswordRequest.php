<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Requests;

use App\Core\Base\Request;
use Illuminate\Validation\Rules;

class ResetPasswordRequest extends Request
{
    public function rules(): array
    {
        return [
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];
    }
}
