<?php

declare(strict_types=1);

namespace App\Modules\CMS\Resources;

use App\Core\Base\Resource;
use App\Modules\CMS\Models\BlogPost;
use Illuminate\Http\Request;

/**
 * @property-read BlogPost $resource
 */
class BlogPostResource extends Resource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->uuid,
            'title' => $this->resource->title,
            'slug' => $this->resource->slug,
            'excerpt' => $this->resource->excerpt,
            'content' => $this->resource->content,
            'featured_image' => $this->resource->featured_image,
            'author' => $this->whenLoaded('author', fn (): ?array => $this->resource->author ? [
                'id' => $this->resource->author->uuid,
                'name' => $this->resource->author->name,
                'avatar' => $this->resource->author->avatar,
            ] : null),
            'category' => $this->whenLoaded('category', fn (): ?array => $this->resource->category ? [
                'id' => $this->resource->category->uuid,
                'name' => $this->resource->category->name,
                'slug' => $this->resource->category->slug,
            ] : null),
            'tags' => $this->whenLoaded('tags', fn (): array => $this->resource->tags->map(fn ($tag): array => [
                'id' => $tag->uuid,
                'name' => $tag->name,
                'slug' => $tag->slug,
            ])->toArray()),
            'status' => $this->resource->status,
            'published_at' => $this->resource->published_at?->toISOString(),
            'seo_title' => $this->resource->seo_title,
            'seo_description' => $this->resource->seo_description,
            'created_at' => $this->resource->created_at?->toISOString(),
            'updated_at' => $this->resource->updated_at?->toISOString(),
        ];
    }
}
