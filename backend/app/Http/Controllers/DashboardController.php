<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Modules\Dashboard\Actions\GetDashboardAction;
use App\Modules\Dashboard\Actions\GetRecentActivityAction;
use App\Modules\Dashboard\Actions\GetStatisticsAction;
use App\Modules\Dashboard\Actions\GetUpcomingEventsAction;
use App\Modules\Dashboard\Resources\DashboardResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardController extends Controller
{
    public function index(Request $request, GetDashboardAction $action): JsonResource
    {
        $this->ensureUserIsActive($request);

        return new DashboardResource($action->execute($request));
    }

    public function statistics(Request $request, GetStatisticsAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        return response()->json([
            'success' => true,
            'data' => $action->execute(),
        ]);
    }

    public function recentActivity(Request $request, GetRecentActivityAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        return response()->json([
            'success' => true,
            'data' => $action->execute(),
        ]);
    }

    public function upcomingEvents(Request $request, GetUpcomingEventsAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        return response()->json([
            'success' => true,
            'data' => $action->execute(),
        ]);
    }

}
