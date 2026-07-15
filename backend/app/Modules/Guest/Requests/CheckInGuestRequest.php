<?php

declare(strict_types=1);

namespace App\Modules\Guest\Requests;

use App\Core\Base\Request;

class CheckInGuestRequest extends Request
{
    public function rules(): array
    {
        return [
            'qr_code' => ['required', 'string', 'max:255'],
        ];
    }
}
