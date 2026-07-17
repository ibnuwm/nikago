<?php

declare(strict_types=1);

namespace App\Modules\CMS\Models;

use Database\Factories\BlogTagFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BlogTag extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected static ?string $factory = BlogTagFactory::class;

    protected static function newFactory(): Factory
    {
        return BlogTagFactory::new();
    }

    protected $table = 'blog_tags';

    protected $fillable = [
        'name',
        'slug',
    ];

    protected static function booted(): void
    {
        static::creating(function (BlogTag $tag): void {
            if (empty($tag->uuid)) {
                $tag->uuid = (string) Str::uuid();
            }
        });
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(BlogPost::class, 'blog_blog_tag', 'blog_tag_id', 'blog_id');
    }
}
