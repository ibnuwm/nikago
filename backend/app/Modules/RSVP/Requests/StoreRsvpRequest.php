<?php

declare(strict_types=1);

namespace App\Modules\RSVP\Requests;

use App\Modules\RSVP\Models\Rsvp;
use Illuminate\Foundation\Http\FormRequest;

class StoreRsvpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'guest_uuid' => ['required', 'string', 'exists:guests,uuid'],
            'attendance' => ['required', 'string', 'in:' . implode(',', Rsvp::attendances())],
            'total_guest' => ['required', 'integer', 'min:1', 'max:100'],
            'message' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
