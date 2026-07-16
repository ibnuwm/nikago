<?php

declare(strict_types=1);

namespace App\Modules\Timeline\Requests;

use App\Core\Base\Request;

class UpdateTimelineRequest extends Request
{
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ];
    }
}
