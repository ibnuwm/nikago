<?php

declare(strict_types=1);

namespace App\Modules\Marketplace\Models;

use App\Modules\Vendor\Models\Vendor;
use Database\Factories\Marketplace\WishlistFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Wishlist extends Model
{
    use HasFactory;

    protected static ?string $factory = WishlistFactory::class;

    protected static function newFactory(): Factory
    {
        return WishlistFactory::new();
    }

    protected $table = 'wishlists';

    protected $fillable = [
        'uuid',
        'tenant_id',
        'user_id',
        'vendor_id',
    ];

    protected static function booted(): void
    {
        static::creating(function (Wishlist $wishlist): void {
            if (empty($wishlist->uuid)) {
                $wishlist->uuid = (string) Str::uuid();
            }
        });
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
}
