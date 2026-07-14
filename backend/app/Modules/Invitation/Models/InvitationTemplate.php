<?php

declare(strict_types=1);

namespace App\Modules\Invitation\Models;

use App\Modules\Authentication\Models\User;
use Database\Factories\InvitationTemplateFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class InvitationTemplate extends Model
{
    use HasFactory;

    protected static ?string $factory = InvitationTemplateFactory::class;

    protected static function newFactory(): Factory
    {
        return InvitationTemplateFactory::new();
    }

    protected $table = 'invitation_templates';

    protected $fillable = [
        'uuid',
        'name',
        'slug',
        'category',
        'description',
        'image',
        'preview_image',
        'is_active',
        'is_premium',
        'sort_order',
        'favorites_count',
    ];

    protected $attributes = [
        'is_active' => true,
        'is_premium' => false,
        'sort_order' => 0,
        'favorites_count' => 0,
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_premium' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (InvitationTemplate $template): void {
            if (empty($template->uuid)) {
                $template->uuid = (string) Str::uuid();
            }
        });
    }

    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'invitation_template_favorites',
            'template_id',
            'user_id'
        );
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeCategory(Builder $query, ?string $category): Builder
    {
        if (! $category) {
            return $query;
        }

        return $query->where('category', $category);
    }

    public function scopePremium(Builder $query): Builder
    {
        return $query->where('is_premium', true);
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (! $search) {
            return $query;
        }

        $escaped = str_replace(['#', '%', '_'], ['##', '#%', '#_'], $search);

        return $query->where(function (Builder $q) use ($escaped): void {
            $q->whereRaw('name LIKE ? ESCAPE \'#\'', ["%{$escaped}%"])
                ->orWhereRaw('description LIKE ? ESCAPE \'#\'', ["%{$escaped}%"]);
        });
    }

    public function scopePopular(Builder $query): Builder
    {
        return $query->orderBy('favorites_count', 'desc');
    }

    public function isFavoritedBy(?int $userId): bool
    {
        if (! $userId) {
            return false;
        }

        return $this->favoritedBy()->where('user_id', $userId)->exists();
    }
}
