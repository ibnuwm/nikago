<?php

declare(strict_types=1);

namespace App\Modules\Guest\Actions;

use App\Core\Base\Action;
use App\Modules\Guest\Models\Guest;
use App\Modules\Wedding\Models\Wedding;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CreateGuestAction extends Action
{
    public function execute(mixed ...$params): ?Guest
    {
        $request = $params[0];

        $user = $request->user();

        $wedding = Wedding::query()
            ->forUser($user->id)
            ->find($request->input('wedding_id'));

        if (! $wedding) {
            return null;
        }

        return Guest::create([
            'tenant_id' => $user->tenant_id ?? 1,
            'wedding_id' => $wedding->id,
            'group_id' => $request->input('group_id'),
            'category_id' => $request->input('category_id'),
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'address' => $request->input('address'),
            'pax' => $request->input('pax', 1),
            'qr_code' => (string) Str::uuid(),
            'status' => $request->input('status', Guest::STATUS_ACTIVE),
        ]);
    }
}
