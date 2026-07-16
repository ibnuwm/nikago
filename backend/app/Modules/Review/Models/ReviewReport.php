<?php

declare(strict_types=1);

namespace App\Modules\Review\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ReviewReport extends Model
{
    protected $table = 'review_reports';

    protected $fillable = [
        'uuid',
        'review_id',
        'user_id',
        'reason',
        'status',
    ];

    protected $attributes = [
        'status' => 'pending',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'string',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (ReviewReport $report): void {
            if (empty($report->uuid)) {
                $report->uuid = (string) Str::uuid();
            }
        });
    }

    public function review(): BelongsTo
    {
        return $this->belongsTo(Review::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }
}
