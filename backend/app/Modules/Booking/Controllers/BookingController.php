<?php

declare(strict_types=1);

namespace App\Modules\Booking\Controllers;

use App\Core\Base\Controller;
use App\Modules\Booking\Actions\CancelBookingAction;
use App\Modules\Booking\Actions\CompleteBookingAction;
use App\Modules\Booking\Actions\ConfirmBookingAction;
use App\Modules\Booking\Actions\CreateBookingAction;
use App\Modules\Booking\Actions\GetBookingAction;
use App\Modules\Booking\Actions\GetBookingCalendarAction;
use App\Modules\Booking\Actions\GetBookingHistoryAction;
use App\Modules\Booking\Actions\ListBookingsAction;
use App\Modules\Booking\Actions\UpdateBookingAction;
use App\Modules\Booking\Actions\UploadContractAction;
use App\Modules\Booking\Requests\StoreBookingRequest;
use App\Modules\Booking\Requests\StoreContractRequest;
use App\Modules\Booking\Requests\UpdateBookingRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BookingController extends Controller
{
    public function __construct(
        private readonly ListBookingsAction $listBookingsAction,
        private readonly CreateBookingAction $createBookingAction,
        private readonly GetBookingAction $getBookingAction,
        private readonly UpdateBookingAction $updateBookingAction,
        private readonly CancelBookingAction $cancelBookingAction,
        private readonly ConfirmBookingAction $confirmBookingAction,
        private readonly CompleteBookingAction $completeBookingAction,
        private readonly UploadContractAction $uploadContractAction,
        private readonly GetBookingCalendarAction $getBookingCalendarAction,
        private readonly GetBookingHistoryAction $getBookingHistoryAction,
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        return $this->listBookingsAction->execute(
            $request->user(),
            $request->only(['per_page', 'status'])
        );
    }

    public function store(StoreBookingRequest $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->createBookingAction->execute(
                $request->user(),
                $request->validated()
            ),
        ], 201);
    }

    public function show(Request $request, string $uuid): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->getBookingAction->execute($request->user(), $uuid),
        ]);
    }

    public function update(UpdateBookingRequest $request, string $uuid): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->updateBookingAction->execute(
                $request->user(),
                $uuid,
                $request->validated()
            ),
        ]);
    }

    public function confirm(Request $request, string $uuid): JsonResponse
    {
        return $this->confirmBookingAction->execute($request->user(), $uuid);
    }

    public function cancel(Request $request, string $uuid): JsonResponse
    {
        return $this->cancelBookingAction->execute($request->user(), $uuid);
    }

    public function complete(Request $request, string $uuid): JsonResponse
    {
        return $this->completeBookingAction->execute($request->user(), $uuid);
    }

    public function contract(StoreContractRequest $request, string $uuid): JsonResponse
    {
        return $this->uploadContractAction->execute(
            $request->user(),
            $uuid,
            $request->validated()
        );
    }

    public function calendar(Request $request): AnonymousResourceCollection
    {
        return $this->getBookingCalendarAction->execute(
            $request->only(['vendor_uuid', 'year', 'month'])
        );
    }

    public function history(Request $request, string $uuid): AnonymousResourceCollection
    {
        return $this->getBookingHistoryAction->execute($request->user(), $uuid);
    }
}
