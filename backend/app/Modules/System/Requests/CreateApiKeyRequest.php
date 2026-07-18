<?php

declare(strict_types=1);

namespace App\Modules\System\Requests;

use App\Core\Base\Request;

class CreateApiKeyRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'expires_at' => ['sometimes', 'nullable', 'date'],
        ];
    }
}
