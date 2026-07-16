<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorPackage extends Model
{
    protected $table = 'vendor_packages';

    protected $fillable = [
        'vendor_id',
        'name',
        'description',
        'price',
        'inclusions',
        'sort_order',
    ];

    protected $attributes = [
        'sort_order' => 0,
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'inclusions' => 'array',
            'sort_order' => 'integer',
        ];
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
}
