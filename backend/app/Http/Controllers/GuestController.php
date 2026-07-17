<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Modules\Guest\Actions\CheckInGuestAction;
use App\Modules\Guest\Actions\CreateGuestAction;
use App\Modules\Guest\Actions\DeleteGuestAction;
use App\Modules\Guest\Actions\ExportGuestsAction;
use App\Modules\Guest\Actions\GetCheckInHistoryAction;
use App\Modules\Guest\Actions\GetGuestAction;
use App\Modules\Guest\Actions\GetGuestsAction;
use App\Modules\Guest\Actions\ImportGuestsAction;
use App\Modules\Guest\Actions\SendInvitationAction;
use App\Modules\Guest\Actions\SendReminderAction;
use App\Modules\Guest\Actions\UpdateGuestAction;
use App\Modules\Guest\Requests\CheckInGuestRequest;
use App\Modules\Guest\Requests\ImportGuestRequest;
use App\Modules\Guest\Requests\StoreGuestRequest;
use App\Modules\Guest\Requests\UpdateGuestRequest;
use App\Modules\Guest\Resources\GuestCheckInResource;
use App\Modules\Guest\Resources\GuestResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index(Request $request, GetGuestsAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $paginated = $action->execute($request);

        return response()->json([
            'success' => true,
            'data' => GuestResource::collection($paginated->items()),
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
            ],
        ]);
    }

    public function store(StoreGuestRequest $request, CreateGuestAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $guest = $action->execute($request);

        if (! $guest) {
            return response()->json([
                'success' => false,
                'message' => 'Wedding not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new GuestResource($guest),
        ], 201);
    }

    public function show(Request $request, string $uuid, GetGuestAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $guest = $action->execute($request, $uuid);

        if (! $guest) {
            return response()->json([
                'success' => false,
                'message' => 'Guest not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new GuestResource($guest),
        ]);
    }

    public function update(UpdateGuestRequest $request, string $uuid, UpdateGuestAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $guest = $action->execute($request, $uuid);

        if (! $guest) {
            return response()->json([
                'success' => false,
                'message' => 'Guest not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new GuestResource($guest),
        ]);
    }

    public function destroy(Request $request, string $uuid, DeleteGuestAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $deleted = $action->execute($request, $uuid);

        if (! $deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Guest not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Guest deleted successfully.',
        ]);
    }

    public function import(ImportGuestRequest $request, ImportGuestsAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $result = $action->execute($request);

        if (! $result['success']) {
            return response()->json([
                'success' => false,
                'message' => 'Wedding not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => "Imported {$result['imported']} guests, {$result['failed']} failed.",
            'data' => $result,
        ]);
    }

    public function export(Request $request, ExportGuestsAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $csv = $action->execute($request);

        return response()->json([
            'success' => true,
            'data' => $csv,
        ]);
    }

    public function checkIn(CheckInGuestRequest $request, string $uuid, CheckInGuestAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $guest = $action->execute($request, $uuid);

        if (! $guest) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid QR code.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new GuestCheckInResource($guest),
        ]);
    }

    public function checkInHistory(Request $request, GetCheckInHistoryAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $paginated = $action->execute($request);

        return response()->json([
            'success' => true,
            'data' => GuestCheckInResource::collection($paginated->items()),
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
            ],
        ]);
    }

    public function sendInvitation(Request $request, SendInvitationAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $guest = $action->execute($request);

        if (! $guest) {
            return response()->json([
                'success' => false,
                'message' => 'Guest not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Invitation sent successfully.',
            'data' => new GuestResource($guest),
        ]);
    }

    public function sendReminder(Request $request, SendReminderAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $guest = $action->execute($request);

        if (! $guest) {
            return response()->json([
                'success' => false,
                'message' => 'Guest not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Reminder sent successfully.',
            'data' => new GuestResource($guest),
        ]);
    }
}
