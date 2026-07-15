<?php

declare(strict_types=1);

namespace App\Modules\Guest\Actions;

use App\Core\Base\Action;
use App\Modules\Guest\Models\Guest;
use Illuminate\Http\Request;

class UpdateGuestAction extends Action
{
    public function execute(mixed ...$params): ?Guest
    {
        $request = $params[0];
        $uuid = $params[1];

        $user = $request->user();

        $guest = Guest::query()
            ->forUser($user->id)
            ->where('uuid', $uuid)
            ->first();

        if (! $guest) {
            return null;
        }

        $guest->update([
            'group_id' => $request->input('group_id', $guest->group_id),
            'category_id' => $request->input('category_id', $guest->category_id),
            'name' => $request->input('name', $guest->name),
            'phone' => $request->input('phone', $guest->phone),
            'email' => $request->input('email', $guest->email),
            'address' => $request->input('address', $guest->address),
            'pax' => $request->input('pax', $guest->pax),
            'status' => $request->input('status', $guest->status),
        ]);

        return $guest->fresh();
    }
}
