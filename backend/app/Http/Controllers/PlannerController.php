<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Modules\Planner\Actions\ExportPlannerAction;
use App\Modules\Planner\Actions\GeneratePlannerAIAction;
use App\Modules\Planner\Actions\GetPlannerDashboardAction;
use App\Modules\Planner\Actions\GetPlannerProgressAction;
use App\Modules\Planner\Actions\GetPlannerSummaryAction;
use App\Modules\Planner\Resources\PlannerResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlannerController extends Controller
{
    public function index(Request $request, GetPlannerDashboardAction $action): JsonResource
    {
        $this->ensureUserIsActive($request);

        return new PlannerResource($action->execute($request));
    }

    public function summary(Request $request, GetPlannerSummaryAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        return response()->json([
            'success' => true,
            'data' => $action->execute($request),
        ]);
    }

    public function progress(Request $request, GetPlannerProgressAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        return response()->json([
            'success' => true,
            'data' => $action->execute($request),
        ]);
    }

    public function generateAi(Request $request, GeneratePlannerAIAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        return response()->json($action->execute($request));
    }

    public function export(Request $request, ExportPlannerAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        return response()->json([
            'success' => true,
            'data' => $action->execute($request),
        ]);
    }
}
