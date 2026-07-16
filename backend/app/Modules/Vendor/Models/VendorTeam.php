<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorTeam extends Model
{
    protected $table = 'vendor_teams';

    protected $fillable = [
        'vendor_id',
        'name',
        'position',
        'photo_url',
        'bio',
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
}
