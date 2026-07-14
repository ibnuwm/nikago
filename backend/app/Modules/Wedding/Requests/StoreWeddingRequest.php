<?php

declare(strict_types=1);

namespace App\Modules\Wedding\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWeddingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'theme' => ['nullable', 'string', 'max:100'],
            'cover_image' => ['nullable', 'string', 'max:500'],
        ];
    }
}
