<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Modules\Authentication\Actions\ResetPasswordAction;
use App\Modules\Authentication\Requests\ResetPasswordRequest;
use Illuminate\Http\JsonResponse;

class ResetPasswordController extends Controller
{
    public function store(ResetPasswordRequest $request, ResetPasswordAction $action): JsonResponse
    {
        $status = $action->execute($request->validated());

        return response()->json([
            'success' => true,
            'message' => $status,
        ]);
    }
}
