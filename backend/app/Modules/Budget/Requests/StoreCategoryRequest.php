<?php

declare(strict_types=1);

namespace App\Modules\Budget\Requests;

use App\Core\Base\Request;

class StoreCategoryRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'allocated_amount' => ['nullable', 'numeric', 'min:0'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
