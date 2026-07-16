<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Requests;

use App\Core\Base\Request;
use Illuminate\Validation\Rule;

class StoreVendorRequest extends Request
{
    public function rules(): array
    {
        return [
            'business_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'province' => ['nullable', 'string', 'max:100'],
            'logo' => ['nullable', 'string', 'max:2048'],
            'cover' => ['nullable', 'string', 'max:2048'],
            'operating_hours' => ['nullable', 'array'],
            'social_media' => ['nullable', 'array'],
            'services' => ['nullable', 'array'],
            'services.*.name' => ['required_with:services', 'string', 'max:255'],
            'services.*.description' => ['nullable', 'string'],
            'services.*.starting_price' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
