<?php

declare(strict_types=1);

namespace App\Modules\Marketplace\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompareRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'vendor_uuids' => ['required', 'array', 'min:2', 'max:4'],
            'vendor_uuids.*' => ['required', 'string', 'exists:vendors,uuid'],
        ];
    }
}
