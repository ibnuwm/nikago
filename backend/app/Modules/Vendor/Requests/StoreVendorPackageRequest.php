<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Requests;

use App\Core\Base\Request;

class StoreVendorPackageRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'inclusions' => ['nullable', 'array'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
