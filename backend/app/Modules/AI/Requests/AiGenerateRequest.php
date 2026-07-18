<?php

declare(strict_types=1);

namespace App\Modules\AI\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AiGenerateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'prompt' => ['required', 'string', 'min:10', 'max:5000'],
            'model' => ['nullable', 'string', 'max:100'],
        ];
    }
}
