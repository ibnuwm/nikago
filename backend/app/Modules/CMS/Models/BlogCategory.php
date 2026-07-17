<?php

declare(strict_types=1);

namespace App\Modules\CMS\Models;

use Database\Factories\BlogCategoryFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BlogCategory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected static ?string $factory = BlogCategoryFactory::class;

    protected static function newFactory(): Factory
    {
        return BlogCategoryFactory::new();
    }

    protected $table = 'blog_categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    protected static function booted(): void
    {
        static::creating(function (BlogCategory $category): void {
            if (empty($category->uuid)) {
                $category->uuid = (string) Str::uuid();
            }
        });
    }

    public function posts(): HasMany
    {
        return $this->hasMany(BlogPost::class, 'category_id');
    }
}
