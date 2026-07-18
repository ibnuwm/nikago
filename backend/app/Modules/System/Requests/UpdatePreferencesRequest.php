<?php

declare(strict_types=1);

namespace App\Modules\System\Requests;

use App\Core\Base\Request;

class UpdatePreferencesRequest extends Request
{
    public function rules(): array
    {
        return [
            'theme' => ['sometimes', 'string', 'in:light,dark,system'],
            'language' => ['sometimes', 'string', 'max:10'],
            'timezone' => ['sometimes', 'string', 'max:50'],
        ];
    }
}
