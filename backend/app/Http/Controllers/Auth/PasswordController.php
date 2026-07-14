<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Modules\Authentication\Actions\UpdatePasswordAction;
use App\Modules\Authentication\Requests\UpdatePasswordRequest;
use Illuminate\Http\JsonResponse;

class PasswordController extends Controller
{
    public function update(UpdatePasswordRequest $request, UpdatePasswordAction $action): JsonResponse
    {
        $action->execute($request, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully.',
        ]);
    }
}
