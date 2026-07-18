<?php

declare(strict_types=1);

namespace App\Modules\CRM\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class LeadActivity extends Model
{
    protected $table = 'lead_activities';

    protected $fillable = [
        'uuid',
        'lead_id',
        'type',
        'description',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'json',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (LeadActivity $activity): void {
            if (empty($activity->uuid)) {
                $activity->uuid = (string) Str::uuid();
            }
        });
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }
}
