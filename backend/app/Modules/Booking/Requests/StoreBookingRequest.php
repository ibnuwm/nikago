<?php

declare(strict_types=1);

namespace App\Modules\Booking\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'vendor_uuid' => ['required', 'string', 'exists:vendors,uuid'],
            'package_id' => ['required', 'integer', 'exists:vendor_packages,id'],
            'event_date' => ['required', 'date', 'after_or_equal:today'],
            'wedding_id' => ['required', 'integer', 'exists:weddings,id'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
