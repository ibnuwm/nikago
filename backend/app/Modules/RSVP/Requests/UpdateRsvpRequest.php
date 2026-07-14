<?php

declare(strict_types=1);

namespace App\Modules\RSVP\Requests;

use App\Modules\RSVP\Models\Rsvp;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRsvpRequest extends FormRequest
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
            'attendance' => ['sometimes', 'string', 'in:' . implode(',', Rsvp::attendances())],
            'total_guest' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'message' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
