<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Requests;

use App\Core\Base\Request;
use App\Modules\Authentication\Models\User;
use Illuminate\Validation\Rules;

class RegisterRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];
    }
}
