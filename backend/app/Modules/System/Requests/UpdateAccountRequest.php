<?php

declare(strict_types=1);

namespace App\Modules\System\Requests;

use App\Core\Base\Request;

class UpdateAccountRequest extends Request
{
    public function rules(): array
    {
        return [
            'timezone' => ['sometimes', 'string', 'max:50'],
            'language' => ['sometimes', 'string', 'max:10'],
        ];
    }
}
