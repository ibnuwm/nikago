<?php

declare(strict_types=1);

namespace App\Modules\Review\Models;

use App\Modules\Booking\Models\Booking;
use App\Modules\Vendor\Models\Vendor;
use Database\Factories\Review\ReviewFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Review extends Model
{
    use HasFactory;

    protected static ?string $factory = ReviewFactory::class;

    protected static function newFactory(): Factory
    {
        return ReviewFactory::new();
    }

    protected $table = 'reviews';

    protected $fillable = [
        'uuid',
        'tenant_id',
        'user_id',
        'booking_id',
        'vendor_id',
        'rating',
        'review',
        'reply',
        'replied_at',
        'status',
    ];

    protected $attributes = [
        'status' => 'approved',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'replied_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Review $review): void {
            if (empty($review->uuid)) {
                $review->uuid = (string) Str::uuid();
            }
        });

        static::saved(function (Review $review): void {
            $review->vendor->updateRating();
        });

        static::deleted(function (Review $review): void {
            if ($review->vendor) {
                $review->vendor->updateRating();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ReviewImage::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(ReviewReport::class);
    }
}
