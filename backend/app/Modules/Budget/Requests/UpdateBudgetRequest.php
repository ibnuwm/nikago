<?php

declare(strict_types=1);

namespace App\Modules\Budget\Requests;

use App\Core\Base\Request;

class UpdateBudgetRequest extends Request
{
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'total_budget' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
