<?php

declare(strict_types=1);

namespace App\Modules\Marketplace\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWishlistRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'vendor_uuid' => ['required', 'string', 'exists:vendors,uuid'],
        ];
    }
}
