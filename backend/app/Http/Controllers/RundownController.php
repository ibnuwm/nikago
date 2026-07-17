<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Modules\Rundown\Actions\CreateRundownAction;
use App\Modules\Rundown\Actions\DeleteRundownAction;
use App\Modules\Rundown\Actions\ExportRundownAction;
use App\Modules\Rundown\Actions\GenerateRundownAIAction;
use App\Modules\Rundown\Actions\GetRundownAction;
use App\Modules\Rundown\Actions\GetRundownsAction;
use App\Modules\Rundown\Actions\PublishRundownAction;
use App\Modules\Rundown\Actions\UpdateRundownAction;
use App\Modules\Rundown\Requests\StoreRundownRequest;
use App\Modules\Rundown\Requests\UpdateRundownRequest;
use App\Modules\Rundown\Resources\RundownResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RundownController extends Controller
{
    public function index(Request $request, GetRundownsAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $paginated = $action->execute($request);

        return response()->json([
            'success' => true,
            'data' => RundownResource::collection($paginated->items()),
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
            ],
        ]);
    }

    public function store(StoreRundownRequest $request, CreateRundownAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $rundown = $action->execute($request);

        if (! $rundown) {
            return response()->json(['success' => false, 'message' => 'Wedding not found.'], 404);
        }

        return response()->json(['success' => true, 'data' => new RundownResource($rundown)], 201);
    }

    public function show(Request $request, string $uuid, GetRundownAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $rundown = $action->execute($request, $uuid);

        if (! $rundown) {
            return response()->json(['success' => false, 'message' => 'Rundown not found.'], 404);
        }

        return response()->json(['success' => true, 'data' => new RundownResource($rundown)]);
    }

    public function update(UpdateRundownRequest $request, string $uuid, UpdateRundownAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $rundown = $action->execute($request, $uuid);

        if (! $rundown) {
            return response()->json(['success' => false, 'message' => 'Rundown not found.'], 404);
        }

        return response()->json(['success' => true, 'data' => new RundownResource($rundown)]);
    }

    public function destroy(Request $request, string $uuid, DeleteRundownAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $deleted = $action->execute($request, $uuid);

        if (! $deleted) {
            return response()->json(['success' => false, 'message' => 'Rundown not found.'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Rundown deleted successfully.']);
    }

    public function publish(Request $request, string $uuid, PublishRundownAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $rundown = $action->execute($request, $uuid);

        if (! $rundown) {
            return response()->json(['success' => false, 'message' => 'Rundown not found.'], 404);
        }

        return response()->json(['success' => true, 'data' => new RundownResource($rundown)]);
    }

    public function export(Request $request, ExportRundownAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $result = $action->execute($request);

        return response()->json([
            'success' => true,
            'data' => $result['data'],
        ]);
    }

    public function generateAi(Request $request, GenerateRundownAIAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $rundown = $action->execute($request);

        if (! $rundown) {
            return response()->json(['success' => false, 'message' => 'No wedding found.'], 404);
        }

        return response()->json(['success' => true, 'data' => new RundownResource($rundown)], 201);
    }
}
