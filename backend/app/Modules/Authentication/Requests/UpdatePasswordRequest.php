<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Requests;

use App\Core\Base\Request;
use Illuminate\Validation\Rules;

class UpdatePasswordRequest extends Request
{
    public function rules(): array
    {
        return [
            'current_password' => ['required', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];
    }
}
