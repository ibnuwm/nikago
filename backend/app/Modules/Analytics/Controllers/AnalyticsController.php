<?php

declare(strict_types=1);

namespace App\Modules\Analytics\Controllers;

use App\Core\Base\Controller;
use App\Modules\Analytics\Actions\ExportAnalyticsAction;
use App\Modules\Analytics\Actions\GetAiAnalyticsAction;
use App\Modules\Analytics\Actions\GetDashboardAnalyticsAction;
use App\Modules\Analytics\Actions\GetGuestAnalyticsAction;
use App\Modules\Analytics\Actions\GetInvitationAnalyticsAction;
use App\Modules\Analytics\Actions\GetRevenueAnalyticsAction;
use App\Modules\Analytics\Actions\GetRsvpAnalyticsAction;
use App\Modules\Analytics\Actions\GetSubscriptionAnalyticsAction;
use App\Modules\Analytics\Actions\GetTrafficAnalyticsAction;
use App\Modules\Analytics\Actions\GetVendorAnalyticsAction;
use App\Modules\Analytics\Requests\AnalyticsFilterRequest;
use Illuminate\Http\JsonResponse;

class AnalyticsController extends Controller
{
    public function __construct(
        private readonly GetDashboardAnalyticsAction $getDashboardAnalyticsAction,
        private readonly GetInvitationAnalyticsAction $getInvitationAnalyticsAction,
        private readonly GetRsvpAnalyticsAction $getRsvpAnalyticsAction,
        private readonly GetGuestAnalyticsAction $getGuestAnalyticsAction,
        private readonly GetVendorAnalyticsAction $getVendorAnalyticsAction,
        private readonly GetSubscriptionAnalyticsAction $getSubscriptionAnalyticsAction,
        private readonly GetRevenueAnalyticsAction $getRevenueAnalyticsAction,
        private readonly GetTrafficAnalyticsAction $getTrafficAnalyticsAction,
        private readonly GetAiAnalyticsAction $getAiAnalyticsAction,
        private readonly ExportAnalyticsAction $exportAnalyticsAction,
    ) {}

    public function dashboard(AnalyticsFilterRequest $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->getDashboardAnalyticsAction->execute($request),
        ]);
    }

    public function invitations(AnalyticsFilterRequest $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->getInvitationAnalyticsAction->execute($request),
        ]);
    }

    public function rsvp(AnalyticsFilterRequest $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->getRsvpAnalyticsAction->execute($request),
        ]);
    }

    public function guests(AnalyticsFilterRequest $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->getGuestAnalyticsAction->execute($request),
        ]);
    }

    public function vendors(AnalyticsFilterRequest $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->getVendorAnalyticsAction->execute($request),
        ]);
    }

    public function subscriptions(AnalyticsFilterRequest $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->getSubscriptionAnalyticsAction->execute($request),
        ]);
    }

    public function revenue(AnalyticsFilterRequest $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->getRevenueAnalyticsAction->execute($request),
        ]);
    }

    public function traffic(AnalyticsFilterRequest $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->getTrafficAnalyticsAction->execute($request),
        ]);
    }

    public function ai(AnalyticsFilterRequest $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->getAiAnalyticsAction->execute($request),
        ]);
    }

    public function export(AnalyticsFilterRequest $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->exportAnalyticsAction->execute($request),
        ]);
    }
}
