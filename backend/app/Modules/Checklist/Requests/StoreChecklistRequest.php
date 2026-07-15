<?php

declare(strict_types=1);

namespace App\Modules\Checklist\Requests;

use App\Core\Base\Request;
use App\Modules\Checklist\Models\ChecklistItem;
use Illuminate\Validation\Rule;

class StoreChecklistRequest extends Request
{
    public function rules(): array
    {
        return [
            'wedding_id' => ['required', 'integer', 'exists:weddings,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ];
    }
}
