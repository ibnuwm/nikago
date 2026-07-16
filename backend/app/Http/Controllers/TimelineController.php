<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Modules\Timeline\Actions\CompleteTimelineAction;
use App\Modules\Timeline\Actions\CompleteTimelineTaskAction;
use App\Modules\Timeline\Actions\CreateTimelineAction;
use App\Modules\Timeline\Actions\DeleteTimelineAction;
use App\Modules\Timeline\Actions\GenerateTimelineAIAction;
use App\Modules\Timeline\Actions\GetTimelineAction;
use App\Modules\Timeline\Actions\GetTimelinesAction;
use App\Modules\Timeline\Actions\ReorderTimelineTasksAction;
use App\Modules\Timeline\Actions\SyncGoogleCalendarAction;
use App\Modules\Timeline\Actions\UncompleteTimelineTaskAction;
use App\Modules\Timeline\Actions\UpdateTimelineAction;
use App\Modules\Timeline\Requests\CompleteTimelineTaskRequest;
use App\Modules\Timeline\Requests\ReorderTimelineTasksRequest;
use App\Modules\Timeline\Requests\StoreTimelineRequest;
use App\Modules\Timeline\Requests\UpdateTimelineRequest;
use App\Modules\Timeline\Resources\TimelineResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TimelineController extends Controller
{
    public function index(Request $request, GetTimelinesAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $paginated = $action->execute($request);

        return response()->json([
            'success' => true,
            'data' => TimelineResource::collection($paginated->items()),
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
            ],
        ]);
    }

    public function store(StoreTimelineRequest $request, CreateTimelineAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $timeline = $action->execute($request);

        if (! $timeline) {
            return response()->json(['success' => false, 'message' => 'Wedding not found.'], 404);
        }

        return response()->json(['success' => true, 'data' => new TimelineResource($timeline)], 201);
    }

    public function show(Request $request, string $uuid, GetTimelineAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $timeline = $action->execute($request, $uuid);

        if (! $timeline) {
            return response()->json(['success' => false, 'message' => 'Timeline not found.'], 404);
        }

        return response()->json(['success' => true, 'data' => new TimelineResource($timeline)]);
    }

    public function update(UpdateTimelineRequest $request, string $uuid, UpdateTimelineAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $timeline = $action->execute($request, $uuid);

        if (! $timeline) {
            return response()->json(['success' => false, 'message' => 'Timeline not found.'], 404);
        }

        return response()->json(['success' => true, 'data' => new TimelineResource($timeline)]);
    }

    public function destroy(Request $request, string $uuid, DeleteTimelineAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $deleted = $action->execute($request, $uuid);

        if (! $deleted) {
            return response()->json(['success' => false, 'message' => 'Timeline not found.'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Timeline deleted successfully.']);
    }

    public function complete(Request $request, string $uuid, CompleteTimelineAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $timeline = $action->execute($request, $uuid);

        if (! $timeline) {
            return response()->json(['success' => false, 'message' => 'Timeline not found.'], 404);
        }

        return response()->json(['success' => true, 'data' => new TimelineResource($timeline)]);
    }

    public function completeTask(CompleteTimelineTaskRequest $request, string $uuid, CompleteTimelineTaskAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $timeline = $action->execute($request, $uuid);

        if (! $timeline) {
            return response()->json(['success' => false, 'message' => 'Timeline or task not found.'], 404);
        }

        return response()->json(['success' => true, 'data' => new TimelineResource($timeline)]);
    }

    public function uncompleteTask(CompleteTimelineTaskRequest $request, string $uuid, UncompleteTimelineTaskAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $timeline = $action->execute($request, $uuid);

        if (! $timeline) {
            return response()->json(['success' => false, 'message' => 'Timeline or task not found.'], 404);
        }

        return response()->json(['success' => true, 'data' => new TimelineResource($timeline)]);
    }

    public function reorder(ReorderTimelineTasksRequest $request, string $uuid, ReorderTimelineTasksAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $timeline = $action->execute($request, $uuid);

        if (! $timeline) {
            return response()->json(['success' => false, 'message' => 'Timeline not found.'], 404);
        }

        return response()->json(['success' => true, 'data' => new TimelineResource($timeline)]);
    }

    public function generateAi(Request $request, GenerateTimelineAIAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $timeline = $action->execute($request);

        if (! $timeline) {
            return response()->json(['success' => false, 'message' => 'No wedding found.'], 404);
        }

        return response()->json(['success' => true, 'data' => new TimelineResource($timeline)], 201);
    }

    public function syncGoogleCalendar(Request $request, string $uuid, SyncGoogleCalendarAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $timeline = $action->execute($request, $uuid);

        if (! $timeline) {
            return response()->json(['success' => false, 'message' => 'Timeline not found.'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Google Calendar sync initiated.', 'data' => new TimelineResource($timeline)]);
    }
}
