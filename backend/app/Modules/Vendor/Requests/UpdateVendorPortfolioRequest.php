<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Requests;

use App\Core\Base\Request;

class UpdateVendorPortfolioRequest extends Request
{
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image_url' => ['sometimes', 'required', 'string', 'max:2048'],
        ];
    }
}
