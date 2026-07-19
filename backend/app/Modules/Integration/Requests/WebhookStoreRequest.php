<?php

declare(strict_types=1);

namespace App\Modules\Integration\Requests;

use App\Core\Base\Request;

class WebhookStoreRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'url' => ['required', 'string', 'url', 'max:500'],
            'events' => ['sometimes', 'nullable', 'array'],
            'events.*' => ['string', 'max:100'],
        ];
    }
}
