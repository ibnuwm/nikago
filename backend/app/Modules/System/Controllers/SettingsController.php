<?php

declare(strict_types=1);

namespace App\Modules\System\Controllers;

use App\Core\Base\Controller;
use App\Modules\System\Actions\CreateApiKeyAction;
use App\Modules\System\Actions\DeleteApiKeyAction;
use App\Modules\System\Actions\GetAccountAction;
use App\Modules\System\Actions\GetNotificationPreferencesAction;
use App\Modules\System\Actions\GetPreferencesAction;
use App\Modules\System\Actions\GetProfileAction;
use App\Modules\System\Actions\ListApiKeysAction;
use App\Modules\System\Actions\UpdateAccountAction;
use App\Modules\System\Actions\UpdateNotificationPreferencesAction;
use App\Modules\System\Actions\UpdatePreferencesAction;
use App\Modules\System\Actions\UpdateProfileAction;
use App\Modules\System\Requests\CreateApiKeyRequest;
use App\Modules\System\Requests\UpdateAccountRequest;
use App\Modules\System\Requests\UpdateNotificationPreferencesRequest;
use App\Modules\System\Requests\UpdatePreferencesRequest;
use App\Modules\System\Requests\UpdateProfileRequest;
use App\Modules\Authentication\Requests\UpdatePasswordRequest;
use App\Modules\Authentication\Actions\UpdatePasswordAction;
use Illuminate\Http\JsonResponse;

class SettingsController extends Controller
{
    public function __construct(
        private readonly GetProfileAction $getProfileAction,
        private readonly UpdateProfileAction $updateProfileAction,
        private readonly GetAccountAction $getAccountAction,
        private readonly UpdateAccountAction $updateAccountAction,
        private readonly UpdatePasswordAction $updatePasswordAction,
        private readonly GetPreferencesAction $getPreferencesAction,
        private readonly UpdatePreferencesAction $updatePreferencesAction,
        private readonly GetNotificationPreferencesAction $getNotificationPreferencesAction,
        private readonly UpdateNotificationPreferencesAction $updateNotificationPreferencesAction,
        private readonly ListApiKeysAction $listApiKeysAction,
        private readonly CreateApiKeyAction $createApiKeyAction,
        private readonly DeleteApiKeyAction $deleteApiKeyAction,
    ) {}

    public function getProfile(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->getProfileAction->execute(request()),
        ]);
    }

    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->updateProfileAction->execute($request, $request->validated()),
        ]);
    }

    public function getAccount(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->getAccountAction->execute(request()),
        ]);
    }

    public function updateAccount(UpdateAccountRequest $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->updateAccountAction->execute($request, $request->validated()),
        ]);
    }

    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $this->updatePasswordAction->execute($request, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully.',
        ]);
    }

    public function getPreferences(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->getPreferencesAction->execute(request()),
        ]);
    }

    public function updatePreferences(UpdatePreferencesRequest $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->updatePreferencesAction->execute($request, $request->validated()),
        ]);
    }

    public function getNotificationPreferences(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->getNotificationPreferencesAction->execute(request()),
        ]);
    }

    public function updateNotificationPreferences(UpdateNotificationPreferencesRequest $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->updateNotificationPreferencesAction->execute($request, $request->validated()),
        ]);
    }

    public function listApiKeys(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->listApiKeysAction->execute(request()),
        ]);
    }

    public function createApiKey(CreateApiKeyRequest $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->createApiKeyAction->execute($request, $request->validated()),
        ]);
    }

    public function deleteApiKey(string $uuid): JsonResponse
    {
        $this->deleteApiKeyAction->execute(request(), $uuid);

        return response()->json([
            'success' => true,
            'message' => 'API key deleted successfully.',
        ]);
    }
}
