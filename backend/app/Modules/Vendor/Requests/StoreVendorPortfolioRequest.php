<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Requests;

use App\Core\Base\Request;

class StoreVendorPortfolioRequest extends Request
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image_url' => ['required', 'string', 'max:2048'],
        ];
    }
}
