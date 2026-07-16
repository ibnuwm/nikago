<?php

declare(strict_types=1);

namespace App\Modules\Booking\Actions;

use App\Modules\Booking\Models\Booking;
use App\Modules\Booking\Models\BookingDocument;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;

class UploadContractAction
{
    public function execute(Authenticatable $user, string $uuid, array $data): JsonResponse
    {
        $booking = Booking::query()
            ->forUser($user->id)
            ->where('uuid', $uuid)
            ->firstOrFail();

        $document = BookingDocument::query()->create([
            'booking_id' => $booking->id,
            'type' => 'contract',
            'file_url' => $data['file_url'],
            'notes' => $data['notes'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'data' => $document,
        ], 201);
    }
}
