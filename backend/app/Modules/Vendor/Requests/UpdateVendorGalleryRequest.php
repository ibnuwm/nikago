<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Requests;

use App\Core\Base\Request;

class UpdateVendorGalleryRequest extends Request
{
    public function rules(): array
    {
        return [
            'image_url' => ['sometimes', 'required', 'string', 'max:2048'],
            'caption' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
