<?php

declare(strict_types=1);

namespace App\Modules\Marketplace\Models;

use App\Modules\Vendor\Models\Vendor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class CompareList extends Model
{
    protected $table = 'compare_lists';

    protected $fillable = [
        'uuid',
        'tenant_id',
        'user_id',
        'vendor_id',
    ];

    protected static function booted(): void
    {
        static::creating(function (CompareList $compare): void {
            if (empty($compare->uuid)) {
                $compare->uuid = (string) Str::uuid();
            }
        });
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
}
