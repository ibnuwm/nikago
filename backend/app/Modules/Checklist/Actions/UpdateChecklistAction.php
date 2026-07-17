<?php

declare(strict_types=1);

namespace App\Modules\Checklist\Actions;

use App\Core\Base\Action;
use App\Modules\Checklist\Models\Checklist;

class UpdateChecklistAction extends Action
{
    public function execute(mixed ...$params): ?Checklist
    {
        $request = $params[0];
        $uuid = $params[1];
        $user = $request->user();

        $checklist = Checklist::query()
            ->forUser($user->id)
            ->where('uuid', $uuid)
            ->first();

        if (! $checklist) {
            return null;
        }

        $checklist->update([
            'title' => $request->input('title', $checklist->title),
            'description' => $request->input('description', $checklist->description),
        ]);

        return $checklist->fresh();
    }
}
