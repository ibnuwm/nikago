<?php

declare(strict_types=1);

namespace App\Modules\CRM\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFollowUpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'string', Rule::in(['call', 'email', 'whatsapp', 'meeting', 'other'])],
            'notes' => ['required', 'string'],
            'follow_up_date' => ['nullable', 'date'],
        ];
    }
}
