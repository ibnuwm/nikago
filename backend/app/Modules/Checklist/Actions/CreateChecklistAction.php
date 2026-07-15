<?php

declare(strict_types=1);

namespace App\Modules\Checklist\Actions;

use App\Core\Base\Action;
use App\Modules\Checklist\Models\Checklist;
use App\Modules\Wedding\Models\Wedding;
use Illuminate\Http\Request;

class CreateChecklistAction extends Action
{
    public function execute(mixed ...$params): ?Checklist
    {
        $request = $params[0];
        $user = $request->user();

        $wedding = Wedding::query()
            ->forUser($user->id)
            ->find($request->input('wedding_id'));

        if (! $wedding) {
            return null;
        }

        return Checklist::create([
            'tenant_id' => $user->tenant_id ?? 1,
            'wedding_id' => $wedding->id,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ]);
    }
}
