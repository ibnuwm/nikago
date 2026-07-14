<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Modules\Authentication\Actions\UpdateProfileAction;
use App\Modules\Authentication\Requests\UpdateProfileRequest;
use App\Modules\Authentication\Resources\UserResource;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    public function update(UpdateProfileRequest $request, UpdateProfileAction $action): JsonResponse
    {
        $user = $action->execute($request, $request->validated());

        return response()->json([
            'success' => true,
            'data' => [
                'user' => new UserResource($user),
            ],
        ]);
    }
}
