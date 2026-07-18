<?php

declare(strict_types=1);

namespace App\Modules\Notification\Controllers;

use App\Core\Base\Controller;
use App\Modules\Notification\Actions\CreateTemplateAction;
use App\Modules\Notification\Actions\DeleteNotificationAction;
use App\Modules\Notification\Actions\DeleteTemplateAction;
use App\Modules\Notification\Actions\GetUnreadCountAction;
use App\Modules\Notification\Actions\ListNotificationsAction;
use App\Modules\Notification\Actions\ListTemplatesAction;
use App\Modules\Notification\Actions\MarkAllAsReadAction;
use App\Modules\Notification\Actions\MarkAsReadAction;
use App\Modules\Notification\Actions\UpdateTemplateAction;
use App\Modules\Notification\Requests\StoreTemplateRequest;
use App\Modules\Notification\Requests\UpdateTemplateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class NotificationController extends Controller
{
    public function __construct(
        private readonly ListNotificationsAction $listNotificationsAction,
        private readonly GetUnreadCountAction $getUnreadCountAction,
        private readonly MarkAsReadAction $markAsReadAction,
        private readonly MarkAllAsReadAction $markAllAsReadAction,
        private readonly DeleteNotificationAction $deleteNotificationAction,
        private readonly ListTemplatesAction $listTemplatesAction,
        private readonly CreateTemplateAction $createTemplateAction,
        private readonly UpdateTemplateAction $updateTemplateAction,
        private readonly DeleteTemplateAction $deleteTemplateAction,
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        return $this->listNotificationsAction->execute(
            $request->user(),
            $request->only(['per_page', 'is_read', 'type'])
        );
    }

    public function unread(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->getUnreadCountAction->execute($request->user()),
        ]);
    }

    public function markAsRead(Request $request, string $uuid): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->markAsReadAction->execute($request->user(), $uuid),
        ]);
    }

    public function markAllAsRead(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->markAllAsReadAction->execute($request->user()),
        ]);
    }

    public function destroy(Request $request, string $uuid): JsonResponse
    {
        $this->deleteNotificationAction->execute($request->user(), $uuid);

        return response()->json(['success' => true]);
    }

    public function templates(Request $request): AnonymousResourceCollection
    {
        return $this->listTemplatesAction->execute(
            $request->only(['per_page', 'is_active', 'channel'])
        );
    }

    public function storeTemplate(StoreTemplateRequest $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->createTemplateAction->execute($request->validated()),
        ], 201);
    }

    public function updateTemplate(UpdateTemplateRequest $request, string $uuid): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->updateTemplateAction->execute($uuid, $request->validated()),
        ]);
    }

    public function destroyTemplate(string $uuid): JsonResponse
    {
        $this->deleteTemplateAction->execute($uuid);

        return response()->json(['success' => true]);
    }
}
