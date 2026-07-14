<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Modules\Authentication\Actions\RegisterAction;
use App\Modules\Authentication\Requests\RegisterRequest;
use App\Modules\Authentication\Resources\UserResource;
use Illuminate\Http\JsonResponse;

class RegisteredUserController extends Controller
{
    public function store(RegisterRequest $request, RegisterAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Register success.',
            'data' => [
                'user' => new UserResource($result['user']),
                'token' => $result['token'],
            ],
        ], 201);
    }
}
