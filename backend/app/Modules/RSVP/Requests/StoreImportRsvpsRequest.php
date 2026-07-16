<?php

declare(strict_types=1);

namespace App\Modules\RSVP\Requests;

use App\Core\Base\Request;

class StoreImportRsvpsRequest extends Request
{
    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:10240'],
        ];
    }
}
