<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Modules\Authentication\Actions\ForgotPasswordAction;
use App\Modules\Authentication\Requests\ForgotPasswordRequest;
use Illuminate\Http\JsonResponse;

class ForgotPasswordController extends Controller
{
    public function store(ForgotPasswordRequest $request, ForgotPasswordAction $action): JsonResponse
    {
        $status = $action->execute($request->validated());

        return response()->json([
            'success' => true,
            'message' => $status,
        ]);
    }
}
