<?php

declare(strict_types=1);

namespace App\Modules\Wedding\Requests;

use App\Core\Base\Request;

class UpdateWeddingRequest extends Request
{
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'theme' => ['nullable', 'string', 'max:100'],
            'cover_image' => ['nullable', 'string', 'max:500'],
        ];
    }
}
