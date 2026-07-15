<?php

declare(strict_types=1);

namespace App\Modules\Checklist\Requests;

use App\Core\Base\Request;

class CompleteChecklistItemRequest extends Request
{
    public function rules(): array
    {
        return [
            'item_uuid' => ['required', 'string', 'exists:checklist_items,uuid'],
        ];
    }
}
