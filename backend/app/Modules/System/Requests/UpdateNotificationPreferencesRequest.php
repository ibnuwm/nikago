<?php

declare(strict_types=1);

namespace App\Modules\System\Requests;

use App\Core\Base\Request;

class UpdateNotificationPreferencesRequest extends Request
{
    public function rules(): array
    {
        return [
            'in_app' => ['sometimes', 'boolean'],
            'email' => ['sometimes', 'boolean'],
            'whatsapp' => ['sometimes', 'boolean'],
        ];
    }
}
