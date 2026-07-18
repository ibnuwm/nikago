<?php

declare(strict_types=1);

namespace App\Modules\AI\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AiChatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'messages' => ['required', 'array', 'min:1'],
            'messages.*.role' => ['required', 'string', Rule::in(['system', 'user', 'assistant'])],
            'messages.*.content' => ['required', 'string'],
            'model' => ['nullable', 'string', 'max:100'],
            'temperature' => ['nullable', 'numeric', 'min:0', 'max:2'],
        ];
    }
}
