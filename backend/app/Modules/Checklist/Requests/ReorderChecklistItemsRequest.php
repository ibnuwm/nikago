<?php

declare(strict_types=1);

namespace App\Modules\Checklist\Requests;

use App\Core\Base\Request;

class ReorderChecklistItemsRequest extends Request
{
    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1'],
            'items.*.uuid' => ['required', 'string', 'exists:checklist_items,uuid'],
            'items.*.sort_order' => ['required', 'integer', 'min:0'],
        ];
    }
}
