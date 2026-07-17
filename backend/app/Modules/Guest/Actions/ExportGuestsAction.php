<?php

declare(strict_types=1);

namespace App\Modules\Guest\Actions;

use App\Core\Base\Action;
use App\Modules\Guest\Models\Guest;

class ExportGuestsAction extends Action
{
    public function execute(mixed ...$params): array
    {
        $request = $params[0];

        $user = $request->user();

        $guests = Guest::query()
            ->forUser($user->id)
            ->get();

        $csv = [];
        $csv[] = ['name', 'phone', 'email', 'address', 'pax', 'status'];

        foreach ($guests as $guest) {
            $csv[] = [
                $guest->name,
                $guest->phone ?? '',
                $guest->email ?? '',
                $guest->address ?? '',
                (string) $guest->pax,
                $guest->status,
            ];
        }

        return $csv;
    }
}
