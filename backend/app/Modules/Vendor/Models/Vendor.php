<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Models;

use Database\Factories\VendorFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Vendor extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected static ?string $factory = VendorFactory::class;

    protected static function newFactory(): Factory
    {
        return VendorFactory::new();
    }

    protected $table = 'vendors';

    protected $fillable = [
        'uuid',
        'tenant_id',
        'user_id',
        'business_name',
        'slug',
        'logo',
        'cover',
        'description',
        'phone',
        'email',
        'address',
        'city',
        'province',
        'operating_hours',
        'social_media',
        'status',
        'rating',
        'total_review',
        'verified_at',
    ];

    protected $attributes = [
        'status' => 'active',
        'rating' => 0,
        'total_review' => 0,
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'decimal:2',
            'total_review' => 'integer',
            'verified_at' => 'datetime',
            'operating_hours' => 'array',
            'social_media' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Vendor $vendor): void {
            if (empty($vendor->uuid)) {
                $vendor->uuid = (string) Str::uuid();
            }
            if (empty($vendor->slug)) {
                $vendor->slug = Str::slug($vendor->business_name) . '-' . Str::random(5);
            }
        });
    }

    public function services(): HasMany
    {
        return $this->hasMany(VendorService::class, 'vendor_id');
    }

    public function packages(): HasMany
    {
        return $this->hasMany(VendorPackage::class, 'vendor_id');
    }

    public function portfolios(): HasMany
    {
        return $this->hasMany(VendorPortfolio::class, 'vendor_id');
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(VendorGallery::class, 'vendor_id');
    }

    public function calendars(): HasMany
    {
        return $this->hasMany(VendorCalendar::class, 'vendor_id');
    }

    public function teams(): HasMany
    {
        return $this->hasMany(VendorTeam::class, 'vendor_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(VendorDocument::class, 'vendor_id');
    }

    public function verifications(): HasMany
    {
        return $this->hasMany(VendorVerification::class, 'vendor_id');
    }

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if ($search === null || $search === '') {
            return $query;
        }

        return $query->where(function (Builder $q) use ($search): void {
            $q->where('business_name', 'like', "%{$search}%")
                ->orWhere('city', 'like', "%{$search}%")
                ->orWhere('province', 'like', "%{$search}%");
        });
    }

    public function scopeFilterByCategory(Builder $query, ?string $category): Builder
    {
        if ($category === null || $category === '') {
            return $query;
        }

        return $query->whereIn('id', function ($q) use ($category): void {
            $q->select('vendor_id')
                ->from('vendor_services')
                ->where('name', $category);
        });
    }

    public function scopeVerified(Builder $query, ?bool $verified): Builder
    {
        if ($verified === null) {
            return $query;
        }

        if ($verified) {
            return $query->whereNotNull('verified_at');
        }

        return $query->whereNull('verified_at');
    }

    public function scopeMinimumRating(Builder $query, ?float $rating): Builder
    {
        if ($rating === null) {
            return $query;
        }

        return $query->where('rating', '>=', $rating);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }
}
