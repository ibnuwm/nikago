<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Modules\Authentication\Actions\ResendVerificationAction;
use App\Modules\Authentication\Actions\VerifyEmailAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function verify(Request $request, VerifyEmailAction $action): JsonResponse
    {
        $action->execute($request);

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully.',
        ]);
    }

    public function resend(Request $request, ResendVerificationAction $action): JsonResponse
    {
        $action->execute($request);

        return response()->json([
            'success' => true,
            'message' => 'Verification link sent.',
        ]);
    }
}
