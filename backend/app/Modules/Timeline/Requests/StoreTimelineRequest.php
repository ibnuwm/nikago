<?php

declare(strict_types=1);

namespace App\Modules\Timeline\Requests;

use App\Core\Base\Request;

class StoreTimelineRequest extends Request
{
    public function rules(): array
    {
        return [
            'wedding_id' => ['required', 'integer', 'exists:weddings,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'tasks' => ['nullable', 'array', 'min:1'],
            'tasks.*.title' => ['required_with:tasks', 'string', 'max:255'],
            'tasks.*.description' => ['nullable', 'string'],
            'tasks.*.priority' => ['nullable', 'string', 'in:low,medium,high'],
            'tasks.*.start_date' => ['nullable', 'date'],
            'tasks.*.due_date' => ['nullable', 'date'],
            'tasks.*.duration_days' => ['nullable', 'integer', 'min:1'],
            'tasks.*.sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
