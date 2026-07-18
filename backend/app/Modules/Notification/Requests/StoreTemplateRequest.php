<?php

declare(strict_types=1);

namespace App\Modules\Notification\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:100', Rule::unique('notification_templates', 'code')],
            'name' => ['required', 'string', 'max:255'],
            'channel' => ['required', 'string', Rule::in(['email', 'whatsapp'])],
            'subject' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'variables' => ['nullable', 'array'],
            'variables.*' => ['string', 'max:100'],
        ];
    }
}
