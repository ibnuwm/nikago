<?php

declare(strict_types=1);

namespace App\Modules\Guest\Requests;

use App\Core\Base\Request;

class ImportGuestRequest extends Request
{
    public function rules(): array
    {
        return [
            'wedding_id' => ['required', 'integer', 'exists:weddings,id'],
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
        ];
    }
}
