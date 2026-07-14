<?php

declare(strict_types=1);

namespace App\Modules\Guest\Models;

use App\Modules\RSVP\Models\Rsvp;
use App\Modules\Wedding\Models\Wedding;
use Database\Factories\GuestFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Guest extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const STATUS_ACTIVE = 'active';

    public const STATUS_INACTIVE = 'inactive';

    protected static ?string $factory = GuestFactory::class;

    protected static function newFactory(): Factory
    {
        return GuestFactory::new();
    }

    protected $table = 'guests';

    protected $fillable = [
        'uuid',
        'tenant_id',
        'wedding_id',
        'group_id',
        'category_id',
        'name',
        'phone',
        'email',
        'address',
        'pax',
        'qr_code',
        'invitation_sent_at',
        'status',
    ];

    protected $attributes = [
        'pax' => 1,
        'status' => self::STATUS_ACTIVE,
    ];

    protected function casts(): array
    {
        return [
            'pax' => 'integer',
            'invitation_sent_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Guest $guest): void {
            if (empty($guest->uuid)) {
                $guest->uuid = (string) Str::uuid();
            }
        });
    }

    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }

    public function rsvp(): HasOne
    {
        return $this->hasOne(Rsvp::class);
    }

    public function scopeForWedding(Builder $query, int $weddingId): Builder
    {
        return $query->where('wedding_id', $weddingId);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (! $search) {
            return $query;
        }

        $escaped = str_replace(['#', '%', '_'], ['##', '#%', '#_'], $search);

        return $query->where(function (Builder $q) use ($escaped): void {
            $q->whereRaw('name LIKE ? ESCAPE \'#\'', ["%{$escaped}%"])
                ->orWhereRaw('phone LIKE ? ESCAPE \'#\'', ["%{$escaped}%"])
                ->orWhereRaw('email LIKE ? ESCAPE \'#\'', ["%{$escaped}%"]);
        });
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }
}
