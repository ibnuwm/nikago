<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Requests;

use App\Core\Base\Request;

class UpdateProfileRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'string', 'lowercase', 'email', 'max:255'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:20'],
            'avatar' => ['sometimes', 'nullable', 'string', 'max:255'],
        ];
    }
}
