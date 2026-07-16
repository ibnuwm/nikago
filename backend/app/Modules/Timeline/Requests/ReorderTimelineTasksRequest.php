<?php

declare(strict_types=1);

namespace App\Modules\Timeline\Requests;

use App\Core\Base\Request;

class ReorderTimelineTasksRequest extends Request
{
    public function rules(): array
    {
        return [
            'tasks' => ['required', 'array', 'min:1'],
            'tasks.*.uuid' => ['required', 'string', 'exists:timeline_tasks,uuid'],
            'tasks.*.sort_order' => ['required', 'integer', 'min:0'],
        ];
    }
}
