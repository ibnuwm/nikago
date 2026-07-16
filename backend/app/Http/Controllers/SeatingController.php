<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Modules\Seating\Actions\AssignGuestAction;
use App\Modules\Seating\Actions\AutoGenerateSeatingAction;
use App\Modules\Seating\Actions\CreateSeatingTableAction;
use App\Modules\Seating\Actions\DeleteSeatingTableAction;
use App\Modules\Seating\Actions\ExportSeatingAction;
use App\Modules\Seating\Actions\GetSeatingTableAction;
use App\Modules\Seating\Actions\GetSeatingTablesAction;
use App\Modules\Seating\Actions\PreviewSeatingAction;
use App\Modules\Seating\Actions\UnassignGuestAction;
use App\Modules\Seating\Actions\UpdateSeatingTableAction;
use App\Modules\Seating\Requests\AssignGuestRequest;
use App\Modules\Seating\Requests\StoreSeatingTableRequest;
use App\Modules\Seating\Requests\UpdateSeatingTableRequest;
use App\Modules\Seating\Resources\SeatingTableResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SeatingController extends Controller
{
    public function index(Request $request, GetSeatingTablesAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $paginated = $action->execute($request);

        return response()->json([
            'success' => true,
            'data' => SeatingTableResource::collection($paginated->items()),
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
            ],
        ]);
    }

    public function store(StoreSeatingTableRequest $request, CreateSeatingTableAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $table = $action->execute($request);

        if (! $table) {
            return response()->json(['success' => false, 'message' => 'Wedding not found.'], 404);
        }

        return response()->json(['success' => true, 'data' => new SeatingTableResource($table)], 201);
    }

    public function show(Request $request, string $uuid, GetSeatingTableAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $table = $action->execute($request, $uuid);

        if (! $table) {
            return response()->json(['success' => false, 'message' => 'Seating table not found.'], 404);
        }

        return response()->json(['success' => true, 'data' => new SeatingTableResource($table)]);
    }

    public function update(UpdateSeatingTableRequest $request, string $uuid, UpdateSeatingTableAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $table = $action->execute($request, $uuid);

        if (! $table) {
            return response()->json(['success' => false, 'message' => 'Seating table not found.'], 404);
        }

        return response()->json(['success' => true, 'data' => new SeatingTableResource($table)]);
    }

    public function destroy(Request $request, string $uuid, DeleteSeatingTableAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $deleted = $action->execute($request, $uuid);

        if (! $deleted) {
            return response()->json(['success' => false, 'message' => 'Seating table not found.'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Seating table deleted successfully.']);
    }

    public function assign(AssignGuestRequest $request, string $uuid, AssignGuestAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $table = $action->execute($request, $uuid);

        if (! $table) {
            return response()->json(['success' => false, 'message' => 'Table, guest, or capacity error.'], 404);
        }

        return response()->json(['success' => true, 'data' => new SeatingTableResource($table)]);
    }

    public function unassign(Request $request, string $uuid, string $assignmentUuid, UnassignGuestAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $deleted = $action->execute($request, $uuid, $assignmentUuid);

        if (! $deleted) {
            return response()->json(['success' => false, 'message' => 'Assignment not found.'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Guest unassigned successfully.']);
    }

    public function autoGenerate(Request $request, AutoGenerateSeatingAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $result = $action->execute($request);

        return response()->json([
            'success' => $result['success'],
            'message' => $result['message'],
            'data' => ['assigned' => $result['assigned']],
        ]);
    }

    public function preview(Request $request, PreviewSeatingAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        return response()->json([
            'success' => true,
            'data' => $action->execute($request),
        ]);
    }

    public function export(Request $request, ExportSeatingAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $result = $action->execute($request);

        return response()->json([
            'success' => true,
            'data' => $result['data'],
        ]);
    }
}
