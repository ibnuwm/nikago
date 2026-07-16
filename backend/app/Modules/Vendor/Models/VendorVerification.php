<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorVerification extends Model
{
    protected $table = 'vendor_verifications';

    protected $fillable = [
        'vendor_id',
        'verified_by',
        'status',
        'notes',
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
}
