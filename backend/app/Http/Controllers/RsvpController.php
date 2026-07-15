<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Modules\RSVP\Actions\CreateRsvpAction;
use App\Modules\RSVP\Actions\DeleteRsvpAction;
use App\Modules\RSVP\Actions\ExportRsvpsAction;
use App\Modules\RSVP\Actions\GetRsvpAction;
use App\Modules\RSVP\Actions\GetRsvpsAction;
use App\Modules\RSVP\Actions\GetRsvpStatisticsAction;
use App\Modules\RSVP\Actions\ImportRsvpsAction;
use App\Modules\RSVP\Actions\UpdateRsvpAction;
use App\Modules\RSVP\Requests\StoreImportRsvpsRequest;
use App\Modules\RSVP\Requests\StoreRsvpRequest;
use App\Modules\RSVP\Requests\UpdateRsvpRequest;
use App\Modules\RSVP\Resources\RsvpResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RsvpController extends Controller
{
    public function index(Request $request, GetRsvpsAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $paginated = $action->execute($request);

        return response()->json([
            'success' => true,
            'data' => RsvpResource::collection($paginated->items()),
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
            ],
        ]);
    }

    public function store(StoreRsvpRequest $request, CreateRsvpAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $rsvp = $action->execute($request);

        return response()->json([
            'success' => true,
            'data' => new RsvpResource($rsvp),
        ], 201);
    }

    public function show(Request $request, string $uuid, GetRsvpAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $rsvp = $action->execute($request, $uuid);

        if (! $rsvp) {
            return response()->json([
                'success' => false,
                'message' => 'RSVP not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new RsvpResource($rsvp),
        ]);
    }

    public function update(UpdateRsvpRequest $request, string $uuid, UpdateRsvpAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $rsvp = $action->execute($request, $uuid);

        if (! $rsvp) {
            return response()->json([
                'success' => false,
                'message' => 'RSVP not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new RsvpResource($rsvp),
        ]);
    }

    public function destroy(Request $request, string $uuid, DeleteRsvpAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $deleted = $action->execute($request, $uuid);

        if (! $deleted) {
            return response()->json([
                'success' => false,
                'message' => 'RSVP not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'RSVP deleted successfully.',
        ]);
    }

    public function statistics(Request $request, GetRsvpStatisticsAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $statistics = $action->execute($request);

        return response()->json([
            'success' => true,
            'data' => $statistics,
        ]);
    }

    public function import(StoreImportRsvpsRequest $request, ImportRsvpsAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $result = $action->execute($request);

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }

    public function export(Request $request, ExportRsvpsAction $action)
    {
        $this->ensureUserIsActive($request);

        return $action->execute($request);
    }

}
