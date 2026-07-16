<?php

declare(strict_types=1);

namespace App\Modules\Booking\Models;

use App\Modules\Vendor\Models\Vendor;
use App\Modules\Vendor\Models\VendorPackage;
use App\Modules\Wedding\Models\Wedding;
use Database\Factories\Booking\BookingFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    protected static ?string $factory = BookingFactory::class;

    protected static function newFactory(): Factory
    {
        return BookingFactory::new();
    }

    protected $table = 'bookings';

    protected $fillable = [
        'uuid',
        'tenant_id',
        'user_id',
        'wedding_id',
        'vendor_id',
        'package_id',
        'booking_date',
        'event_date',
        'subtotal',
        'discount',
        'total',
        'status',
        'notes',
    ];

    protected $attributes = [
        'status' => 'pending',
        'discount' => 0,
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
            'event_date' => 'date',
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Booking $booking): void {
            if (empty($booking->uuid)) {
                $booking->uuid = (string) Str::uuid();
            }
        });
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(VendorPackage::class, 'package_id');
    }

    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(BookingItem::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(BookingHistory::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(BookingDocument::class);
    }

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }
}
