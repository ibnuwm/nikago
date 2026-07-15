<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Modules\Invitation\Actions\CreateInvitationAction;
use App\Modules\Invitation\Actions\DeleteInvitationAction;
use App\Modules\Invitation\Actions\DraftInvitationAction;
use App\Modules\Invitation\Actions\DuplicateInvitationAction;
use App\Modules\Invitation\Actions\GetInvitationAction;
use App\Modules\Invitation\Actions\GetInvitationsAction;
use App\Modules\Invitation\Actions\PreviewInvitationAction;
use App\Modules\Invitation\Actions\PublishInvitationAction;
use App\Modules\Invitation\Actions\UpdateInvitationAction;
use App\Modules\Invitation\Requests\StoreInvitationRequest;
use App\Modules\Invitation\Requests\UpdateInvitationRequest;
use App\Modules\Invitation\Resources\InvitationResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InvitationController extends Controller
{
    public function index(Request $request, GetInvitationsAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $paginated = $action->execute($request);

        return response()->json([
            'success' => true,
            'data' => InvitationResource::collection($paginated->items()),
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
            ],
        ]);
    }

    public function store(StoreInvitationRequest $request, CreateInvitationAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $invitation = $action->execute($request);

        return response()->json([
            'success' => true,
            'data' => new InvitationResource($invitation),
        ], 201);
    }

    public function show(Request $request, string $uuid, GetInvitationAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $invitation = $action->execute($request, $uuid);

        if (! $invitation) {
            return response()->json([
                'success' => false,
                'message' => 'Invitation not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new InvitationResource($invitation),
        ]);
    }

    public function update(UpdateInvitationRequest $request, string $uuid, UpdateInvitationAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $invitation = $action->execute($request, $uuid);

        if (! $invitation) {
            return response()->json([
                'success' => false,
                'message' => 'Invitation not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new InvitationResource($invitation),
        ]);
    }

    public function destroy(Request $request, string $uuid, DeleteInvitationAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $deleted = $action->execute($request, $uuid);

        if (! $deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Invitation not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Invitation deleted successfully.',
        ]);
    }

    public function publish(Request $request, string $uuid, PublishInvitationAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $invitation = $action->execute($request, $uuid);

        if (! $invitation) {
            return response()->json([
                'success' => false,
                'message' => 'Invitation not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new InvitationResource($invitation),
        ]);
    }

    public function draft(Request $request, string $uuid, DraftInvitationAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $invitation = $action->execute($request, $uuid);

        if (! $invitation) {
            return response()->json([
                'success' => false,
                'message' => 'Invitation not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new InvitationResource($invitation),
        ]);
    }

    public function duplicate(Request $request, string $uuid, DuplicateInvitationAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $invitation = $action->execute($request, $uuid);

        if (! $invitation) {
            return response()->json([
                'success' => false,
                'message' => 'Invitation not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new InvitationResource($invitation),
        ], 201);
    }

    public function preview(Request $request, string $uuid, PreviewInvitationAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $invitation = $action->execute($request, $uuid);

        if (! $invitation) {
            return response()->json([
                'success' => false,
                'message' => 'Invitation not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new InvitationResource($invitation),
        ]);
    }

}
