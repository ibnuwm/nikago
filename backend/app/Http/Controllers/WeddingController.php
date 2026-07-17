<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Modules\Wedding\Actions\ArchiveWeddingAction;
use App\Modules\Wedding\Actions\CreateWeddingAction;
use App\Modules\Wedding\Actions\DeleteWeddingAction;
use App\Modules\Wedding\Actions\GetWeddingAction;
use App\Modules\Wedding\Actions\GetWeddingsAction;
use App\Modules\Wedding\Actions\PublishWeddingAction;
use App\Modules\Wedding\Actions\UpdateWeddingAction;
use App\Modules\Wedding\Requests\StoreWeddingRequest;
use App\Modules\Wedding\Requests\UpdateWeddingRequest;
use App\Modules\Wedding\Resources\WeddingResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WeddingController extends Controller
{
    public function index(Request $request, GetWeddingsAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $paginated = $action->execute($request);

        return response()->json([
            'success' => true,
            'data' => WeddingResource::collection($paginated->items()),
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
            ],
        ]);
    }

    public function store(StoreWeddingRequest $request, CreateWeddingAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $wedding = $action->execute($request);

        return response()->json([
            'success' => true,
            'data' => new WeddingResource($wedding),
        ], 201);
    }

    public function show(Request $request, string $uuid, GetWeddingAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $wedding = $action->execute($request, $uuid);

        if (! $wedding) {
            return response()->json([
                'success' => false,
                'message' => 'Wedding not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new WeddingResource($wedding),
        ]);
    }

    public function update(UpdateWeddingRequest $request, string $uuid, UpdateWeddingAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $wedding = $action->execute($request, $uuid);

        if (! $wedding) {
            return response()->json([
                'success' => false,
                'message' => 'Wedding not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new WeddingResource($wedding),
        ]);
    }

    public function destroy(Request $request, string $uuid, DeleteWeddingAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $deleted = $action->execute($request, $uuid);

        if (! $deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Wedding not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Wedding deleted successfully.',
        ]);
    }

    public function publish(Request $request, string $uuid, PublishWeddingAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $wedding = $action->execute($request, $uuid);

        if (! $wedding) {
            return response()->json([
                'success' => false,
                'message' => 'Wedding not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new WeddingResource($wedding),
        ]);
    }

    public function archive(Request $request, string $uuid, ArchiveWeddingAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $wedding = $action->execute($request, $uuid);

        if (! $wedding) {
            return response()->json([
                'success' => false,
                'message' => 'Wedding not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new WeddingResource($wedding),
        ]);
    }
}
