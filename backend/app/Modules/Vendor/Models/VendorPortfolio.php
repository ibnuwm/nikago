<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorPortfolio extends Model
{
    protected $table = 'vendor_portfolios';

    protected $fillable = [
        'vendor_id',
        'title',
        'description',
        'image_url',
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
}
