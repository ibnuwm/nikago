<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorCalendar extends Model
{
    protected $table = 'vendor_calendars';

    protected $fillable = [
        'vendor_id',
        'booked_date',
        'status',
        'notes',
    ];

    protected $attributes = [
        'status' => 'booked',
    ];

    protected function casts(): array
    {
        return [
            'booked_date' => 'date',
        ];
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
}
