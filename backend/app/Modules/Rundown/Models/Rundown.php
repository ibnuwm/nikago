<?php

declare(strict_types=1);

namespace App\Modules\Rundown\Models;

use App\Modules\Wedding\Models\Wedding;
use Database\Factories\RundownFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Rundown extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const STATUS_DRAFT = 'draft';

    public const STATUS_PUBLISHED = 'published';

    public const STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_PUBLISHED,
    ];

    protected static ?string $factory = RundownFactory::class;

    protected static function newFactory(): Factory
    {
        return RundownFactory::new();
    }

    protected $table = 'rundowns';

    protected $fillable = [
        'uuid',
        'tenant_id',
        'wedding_id',
        'title',
        'description',
        'status',
        'published_at',
    ];

    protected $attributes = [
        'status' => self::STATUS_DRAFT,
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Rundown $rundown): void {
            if (empty($rundown->uuid)) {
                $rundown->uuid = (string) Str::uuid();
            }
        });
    }

    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(RundownItem::class, 'rundown_id');
    }

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->whereIn('wedding_id', function ($q) use ($userId): void {
            $q->select('id')->from('weddings')->where('user_id', $userId);
        });
    }

    public function scopeForWedding(Builder $query, int $weddingId): Builder
    {
        return $query->where('wedding_id', $weddingId);
    }

    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    public function publish(): void
    {
        $this->update([
            'status' => self::STATUS_PUBLISHED,
            'published_at' => $this->published_at ?? now(),
        ]);
    }
}
