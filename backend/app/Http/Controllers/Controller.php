<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class Controller
{
    protected function ensureUserIsActive(Request $request): void
    {
        $user = $request->user();

        if ($user && ! $user->isActive()) {
            abort(403, 'Your account is not active.');
        }
    }
}
