<?php

declare(strict_types=1);

namespace App\Modules\CRM\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class LeadFollowUp extends Model
{
    protected $table = 'lead_follow_ups';

    protected $fillable = [
        'uuid',
        'lead_id',
        'type',
        'notes',
        'follow_up_date',
        'is_completed',
        'completed_at',
    ];

    protected $attributes = [
        'is_completed' => false,
    ];

    protected function casts(): array
    {
        return [
            'is_completed' => 'boolean',
            'follow_up_date' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (LeadFollowUp $followUp): void {
            if (empty($followUp->uuid)) {
                $followUp->uuid = (string) Str::uuid();
            }
        });
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }
}
