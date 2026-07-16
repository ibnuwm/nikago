<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Models;

use Database\Factories\VendorServiceFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorService extends Model
{
    use HasFactory;

    protected static ?string $factory = VendorServiceFactory::class;

    protected static function newFactory(): Factory
    {
        return VendorServiceFactory::new();
    }

    protected $table = 'vendor_services';

    protected $fillable = [
        'vendor_id',
        'name',
        'description',
        'starting_price',
    ];

    protected $attributes = [];

    protected function casts(): array
    {
        return [
            'starting_price' => 'decimal:2',
        ];
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
}
