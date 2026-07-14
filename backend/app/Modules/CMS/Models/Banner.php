<?php

declare(strict_types=1);

namespace App\Modules\CMS\Models;

use Database\Factories\CmsBannerFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Banner extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected static ?string $factory = CmsBannerFactory::class;

    protected static function newFactory(): Factory
    {
        return CmsBannerFactory::new();
    }

    protected $table = 'cms_banners';

    protected $fillable = [
        'title',
        'subtitle',
        'image',
        'link',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Banner $banner): void {
            if (empty($banner->uuid)) {
                $banner->uuid = (string) Str::uuid();
            }
        });
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order');
    }
}
