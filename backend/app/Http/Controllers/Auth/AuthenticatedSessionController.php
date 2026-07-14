<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Modules\Authentication\Actions\LoginAction;
use App\Modules\Authentication\Actions\LogoutAction;
use App\Modules\Authentication\Requests\LoginRequest;
use App\Modules\Authentication\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthenticatedSessionController extends Controller
{
    public function store(LoginRequest $request, LoginAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());

        return response()->json([
            'success' => true,
            'data' => [
                'user' => new UserResource($result['user']),
                'token' => $result['token'],
            ],
        ]);
    }

    public function destroy(Request $request, LogoutAction $action): JsonResponse
    {
        $action->execute($request);

        return response()->json([
            'success' => true,
            'message' => 'Logout success.',
        ]);
    }
}
