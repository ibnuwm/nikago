<?php

declare(strict_types=1);

namespace App\Modules\Guest\Requests;

use App\Core\Base\Request;
use Illuminate\Validation\Rule;

class StoreGuestRequest extends Request
{
    public function rules(): array
    {
        return [
            'wedding_id' => ['required', 'integer', 'exists:weddings,id'],
            'group_id' => ['nullable', 'integer'],
            'category_id' => ['nullable', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:25'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'pax' => ['nullable', 'integer', 'min:1', 'max:32767'],
            'status' => ['nullable', 'string', Rule::in(['active', 'inactive'])],
        ];
    }
}
