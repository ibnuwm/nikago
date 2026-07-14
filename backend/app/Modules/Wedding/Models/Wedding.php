<?php

declare(strict_types=1);

namespace App\Modules\Wedding\Models;

use App\Modules\Authentication\Models\User;
use App\Modules\System\Models\Tenant;
use Database\Factories\WeddingFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Wedding extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const STATUS_DRAFT = 'draft';

    public const STATUS_PUBLISHED = 'published';

    public const STATUS_ARCHIVED = 'archived';

    protected static ?string $factory = WeddingFactory::class;

    protected static function newFactory(): Factory
    {
        return WeddingFactory::new();
    }

    protected $table = 'weddings';

    protected $fillable = [
        'tenant_id',
        'user_id',
        'title',
        'slug',
        'status',
        'theme',
        'cover_image',
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
        static::creating(function (Wedding $wedding): void {
            if (empty($wedding->uuid)) {
                $wedding->uuid = (string) Str::uuid();
            }

            if (empty($wedding->slug)) {
                $wedding->slug = Str::slug($wedding->title);
            }
        });
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForTenant(Builder $query, int $tenantId): Builder
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (! $search) {
            return $query;
        }

        $escaped = str_replace(['#', '%', '_'], ['##', '#%', '#_'], $search);

        return $query->where(function (Builder $q) use ($escaped): void {
            $q->whereRaw('title LIKE ? ESCAPE \'#\'', ["%{$escaped}%"]);
        });
    }

    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isArchived(): bool
    {
        return $this->status === self::STATUS_ARCHIVED;
    }
}
