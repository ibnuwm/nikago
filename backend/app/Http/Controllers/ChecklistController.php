<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Modules\Checklist\Actions\CompleteChecklistItemAction;
use App\Modules\Checklist\Actions\CreateChecklistAction;
use App\Modules\Checklist\Actions\DeleteChecklistAction;
use App\Modules\Checklist\Actions\DuplicateChecklistAction;
use App\Modules\Checklist\Actions\GenerateChecklistAIAction;
use App\Modules\Checklist\Actions\GetChecklistAction;
use App\Modules\Checklist\Actions\GetChecklistsAction;
use App\Modules\Checklist\Actions\ReorderChecklistItemsAction;
use App\Modules\Checklist\Actions\UncompleteChecklistItemAction;
use App\Modules\Checklist\Actions\UpdateChecklistAction;
use App\Modules\Checklist\Requests\CompleteChecklistItemRequest;
use App\Modules\Checklist\Requests\ReorderChecklistItemsRequest;
use App\Modules\Checklist\Requests\StoreChecklistRequest;
use App\Modules\Checklist\Requests\UpdateChecklistRequest;
use App\Modules\Checklist\Resources\ChecklistResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChecklistController extends Controller
{
    public function index(Request $request, GetChecklistsAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $paginated = $action->execute($request);

        return response()->json([
            'success' => true,
            'data' => ChecklistResource::collection($paginated->items()),
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
            ],
        ]);
    }

    public function store(StoreChecklistRequest $request, CreateChecklistAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $checklist = $action->execute($request);

        if (! $checklist) {
            return response()->json(['success' => false, 'message' => 'Wedding not found.'], 404);
        }

        return response()->json(['success' => true, 'data' => new ChecklistResource($checklist)], 201);
    }

    public function show(Request $request, string $uuid, GetChecklistAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $checklist = $action->execute($request, $uuid);

        if (! $checklist) {
            return response()->json(['success' => false, 'message' => 'Checklist not found.'], 404);
        }

        return response()->json(['success' => true, 'data' => new ChecklistResource($checklist)]);
    }

    public function update(UpdateChecklistRequest $request, string $uuid, UpdateChecklistAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $checklist = $action->execute($request, $uuid);

        if (! $checklist) {
            return response()->json(['success' => false, 'message' => 'Checklist not found.'], 404);
        }

        return response()->json(['success' => true, 'data' => new ChecklistResource($checklist)]);
    }

    public function destroy(Request $request, string $uuid, DeleteChecklistAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $deleted = $action->execute($request, $uuid);

        if (! $deleted) {
            return response()->json(['success' => false, 'message' => 'Checklist not found.'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Checklist deleted successfully.']);
    }

    public function complete(CompleteChecklistItemRequest $request, string $uuid, CompleteChecklistItemAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $checklist = $action->execute($request, $uuid);

        if (! $checklist) {
            return response()->json(['success' => false, 'message' => 'Checklist or item not found.'], 404);
        }

        return response()->json(['success' => true, 'data' => new ChecklistResource($checklist)]);
    }

    public function uncomplete(CompleteChecklistItemRequest $request, string $uuid, UncompleteChecklistItemAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $checklist = $action->execute($request, $uuid);

        if (! $checklist) {
            return response()->json(['success' => false, 'message' => 'Checklist or item not found.'], 404);
        }

        return response()->json(['success' => true, 'data' => new ChecklistResource($checklist)]);
    }

    public function reorder(ReorderChecklistItemsRequest $request, string $uuid, ReorderChecklistItemsAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $checklist = $action->execute($request, $uuid);

        if (! $checklist) {
            return response()->json(['success' => false, 'message' => 'Checklist not found.'], 404);
        }

        return response()->json(['success' => true, 'data' => new ChecklistResource($checklist)]);
    }

    public function duplicate(Request $request, string $uuid, DuplicateChecklistAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $checklist = $action->execute($request, $uuid);

        if (! $checklist) {
            return response()->json(['success' => false, 'message' => 'Checklist not found.'], 404);
        }

        return response()->json(['success' => true, 'data' => new ChecklistResource($checklist)], 201);
    }

    public function generateAi(Request $request, GenerateChecklistAIAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        return response()->json($action->execute($request));
    }
}
