<?php

declare(strict_types=1);

namespace App\Modules\Notification\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['sometimes', 'required', 'string', 'max:100',
                Rule::unique('notification_templates', 'code')->ignore($this->route('uuid'), 'uuid'),
            ],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'channel' => ['sometimes', 'required', 'string', Rule::in(['email', 'whatsapp'])],
            'subject' => ['nullable', 'string', 'max:255'],
            'content' => ['sometimes', 'required', 'string'],
            'variables' => ['nullable', 'array'],
            'variables.*' => ['string', 'max:100'],
        ];
    }
}
