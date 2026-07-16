<?php

declare(strict_types=1);

namespace App\Modules\Seating\Requests;

use App\Core\Base\Request;

class AssignGuestRequest extends Request
{
    public function rules(): array
    {
        return [
            'guest_id' => ['required', 'string', 'exists:guests,uuid'],
            'seat_number' => ['nullable', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }
}
