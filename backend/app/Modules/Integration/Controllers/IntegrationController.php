<?php

declare(strict_types=1);

namespace App\Modules\Integration\Controllers;

use App\Core\Base\Controller;
use App\Modules\Integration\Services\IntegrationService;
use App\Modules\Integration\Services\WebhookService;
use App\Modules\Integration\Requests\ConnectRequest;
use App\Modules\Integration\Requests\WebhookStoreRequest;
use App\Modules\Integration\Resources\IntegrationResource;
use App\Modules\Integration\Resources\WebhookResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IntegrationController extends Controller
{
    public function __construct(
        private readonly IntegrationService $integrationService,
        private readonly WebhookService $webhookService,
    ) {}

    public function index(): JsonResponse
    {
        $integrations = $this->integrationService->getUserIntegrations(request()->user()->id);

        return response()->json([
            'success' => true,
            'data' => $integrations,
        ]);
    }

    public function providers(): JsonResponse
    {
        $providers = $this->integrationService->getProviders();

        return response()->json([
            'success' => true,
            'data' => $providers,
        ]);
    }

    public function googleConnect(ConnectRequest $request): JsonResponse
    {
        $this->integrationService->connect(
            $request->user()->id,
            'GOOGLE_OAUTH',
            $request->validated(),
        );

        return response()->json([
            'success' => true,
            'message' => 'Google OAuth connected successfully.',
        ]);
    }

    public function googleDisconnect(Request $request): JsonResponse
    {
        $this->integrationService->disconnect($request->user()->id, 'GOOGLE_OAUTH');

        return response()->json([
            'success' => true,
            'message' => 'Google OAuth disconnected successfully.',
        ]);
    }

    public function calendarConnect(ConnectRequest $request): JsonResponse
    {
        $this->integrationService->connect(
            $request->user()->id,
            'GOOGLE_CALENDAR',
            $request->validated(),
        );

        return response()->json([
            'success' => true,
            'message' => 'Google Calendar connected successfully.',
        ]);
    }

    public function calendarDisconnect(Request $request): JsonResponse
    {
        $this->integrationService->disconnect($request->user()->id, 'GOOGLE_CALENDAR');

        return response()->json([
            'success' => true,
            'message' => 'Google Calendar disconnected successfully.',
        ]);
    }

    public function whatsappConnect(ConnectRequest $request): JsonResponse
    {
        $this->integrationService->connect(
            $request->user()->id,
            'WHATSAPP',
            $request->validated(),
        );

        return response()->json([
            'success' => true,
            'message' => 'WhatsApp API connected successfully.',
        ]);
    }

    public function whatsappDisconnect(Request $request): JsonResponse
    {
        $this->integrationService->disconnect($request->user()->id, 'WHATSAPP');

        return response()->json([
            'success' => true,
            'message' => 'WhatsApp API disconnected successfully.',
        ]);
    }

    public function webhooks(): JsonResponse
    {
        $webhooks = $this->webhookService->list(request()->user()->id);

        return response()->json([
            'success' => true,
            'data' => $webhooks,
        ]);
    }

    public function storeWebhook(WebhookStoreRequest $request): JsonResponse
    {
        $webhook = $this->webhookService->create($request->user()->id, $request->validated());

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $webhook->uuid,
                'uuid' => $webhook->uuid,
                'name' => $webhook->name,
                'url' => $webhook->url,
                'events' => $webhook->events,
                'is_active' => $webhook->is_active,
                'created_at' => $webhook->created_at?->toISOString(),
            ],
        ]);
    }

    public function deleteWebhook(string $uuid): JsonResponse
    {
        $deleted = $this->webhookService->delete(request()->user()->id, $uuid);

        if (! $deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Webhook not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Webhook deleted successfully.',
        ]);
    }

    public function test(Request $request): JsonResponse
    {
        $provider = $request->input('provider');
        $userId = $request->user()->id;

        $connected = $this->integrationService->isConnected($userId, $provider);

        return response()->json([
            'success' => true,
            'data' => [
                'provider' => $provider,
                'is_connected' => $connected,
                'status' => $connected ? 'connected' : 'disconnected',
            ],
        ]);
    }
}
