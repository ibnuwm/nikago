<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Requests;

use App\Core\Base\Request;

class ForgotPasswordRequest extends Request
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
        ];
    }
}
