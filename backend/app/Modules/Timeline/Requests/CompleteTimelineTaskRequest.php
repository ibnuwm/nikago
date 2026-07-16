<?php

declare(strict_types=1);

namespace App\Modules\Timeline\Requests;

use App\Core\Base\Request;

class CompleteTimelineTaskRequest extends Request
{
    public function rules(): array
    {
        return [
            'task_uuid' => ['required', 'string', 'exists:timeline_tasks,uuid'],
        ];
    }
}
