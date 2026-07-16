<?php

declare(strict_types=1);

namespace App\Modules\Booking\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContractRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'file_url' => ['required', 'string', 'url'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
