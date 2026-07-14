<?php

declare(strict_types=1);

namespace App\Modules\Invitation\Models;

use App\Modules\Wedding\Models\Wedding;
use Database\Factories\InvitationFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Invitation extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const STATUS_DRAFT = 'draft';

    public const STATUS_PUBLISHED = 'published';

    protected static ?string $factory = InvitationFactory::class;

    protected static function newFactory(): Factory
    {
        return InvitationFactory::new();
    }

    protected $table = 'invitations';

    protected $fillable = [
        'tenant_id',
        'wedding_id',
        'template_id',
        'theme_id',
        'title',
        'slug',
        'cover_image',
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
        static::creating(function (Invitation $invitation): void {
            if (empty($invitation->uuid)) {
                $invitation->uuid = (string) Str::uuid();
            }

            if (empty($invitation->slug)) {
                $invitation->slug = Str::slug($invitation->title);
            }
        });
    }

    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->whereHas('wedding', function ($q) use ($userId): void {
            $q->where('user_id', $userId);
        });
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
}
