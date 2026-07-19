<?php

declare(strict_types=1);

namespace App\Modules\Integration\Requests;

use App\Core\Base\Request;

class ConnectRequest extends Request
{
    public function rules(): array
    {
        return [
            'access_token' => ['sometimes', 'string'],
            'refresh_token' => ['sometimes', 'nullable', 'string'],
            'api_key' => ['sometimes', 'string'],
            'api_secret' => ['sometimes', 'nullable', 'string'],
        ];
    }
}
