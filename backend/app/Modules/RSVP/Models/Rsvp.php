<?php

declare(strict_types=1);

namespace App\Modules\RSVP\Models;

use App\Modules\Guest\Models\Guest;
use Database\Factories\RsvpFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Rsvp extends Model
{
    use HasFactory;

    public const ATTENDANCE_YES = 'YES';

    public const ATTENDANCE_NO = 'NO';

    public const ATTENDANCE_MAYBE = 'MAYBE';

    protected static ?string $factory = RsvpFactory::class;

    protected static function newFactory(): Factory
    {
        return RsvpFactory::new();
    }

    protected $table = 'rsvps';

    protected $fillable = [
        'uuid',
        'tenant_id',
        'guest_id',
        'attendance',
        'total_guest',
        'message',
        'confirmed_at',
    ];

    protected $attributes = [
        'total_guest' => 1,
    ];

    protected function casts(): array
    {
        return [
            'total_guest' => 'integer',
            'confirmed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Rsvp $rsvp): void {
            if (empty($rsvp->uuid)) {
                $rsvp->uuid = (string) Str::uuid();
            }

            if (empty($rsvp->confirmed_at)) {
                $rsvp->confirmed_at = now()->toDateTimeString();
            }
        });
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(RsvpLog::class);
    }

    public function scopeForTenant(Builder $query, int $tenantId): Builder
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeAttendance(Builder $query, string $attendance): Builder
    {
        return $query->where('attendance', $attendance);
    }

    public static function attendances(): array
    {
        return [
            self::ATTENDANCE_YES,
            self::ATTENDANCE_NO,
            self::ATTENDANCE_MAYBE,
        ];
    }
}
