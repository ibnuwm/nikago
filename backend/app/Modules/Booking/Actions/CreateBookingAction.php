<?php

declare(strict_types=1);

namespace App\Modules\Booking\Actions;

use App\Modules\Booking\Models\Booking;
use App\Modules\Booking\Models\BookingHistory;
use App\Modules\Booking\Resources\BookingResource;
use App\Modules\Vendor\Models\Vendor;
use App\Modules\Vendor\Models\VendorPackage;
use Illuminate\Contracts\Auth\Authenticatable;

class CreateBookingAction
{
    public function execute(Authenticatable $user, array $data): BookingResource
    {
        $vendor = Vendor::query()->where('uuid', $data['vendor_uuid'])->firstOrFail();
        $package = VendorPackage::query()->findOrFail($data['package_id']);

        $booking = Booking::query()->create([
            'tenant_id' => $user->tenant_id ?? 1,
            'user_id' => $user->id,
            'wedding_id' => $data['wedding_id'],
            'vendor_id' => $vendor->id,
            'package_id' => $package->id,
            'booking_date' => now()->toDateString(),
            'event_date' => $data['event_date'],
            'subtotal' => $package->price,
            'discount' => 0,
            'total' => $package->price,
            'status' => 'pending',
            'notes' => $data['notes'] ?? null,
        ]);

        BookingHistory::query()->create([
            'booking_id' => $booking->id,
            'status_from' => null,
            'status_to' => 'pending',
            'changed_by' => $user->id,
        ]);

        return new BookingResource(
            $booking->load(['vendor', 'package', 'histories'])
        );
    }
}
