<?php

declare(strict_types=1);

namespace App\Modules\CMS\Actions;

use App\Core\Base\Action;
use App\Modules\CMS\Models\BlogPost;
use Illuminate\Support\Collection;

class GetSitemapUrlsAction extends Action
{
    public function execute(mixed ...$params): Collection
    {
        $urls = collect([
            ['loc' => '/', 'priority' => '1.0', 'changefreq' => 'monthly'],
            ['loc' => '/login', 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['loc' => '/register', 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['loc' => '/blog', 'priority' => '0.9', 'changefreq' => 'weekly'],
        ]);

        $blogPosts = BlogPost::query()
            ->published()
            ->select('slug', 'updated_at')
            ->get()
            ->map(fn (BlogPost $post): array => [
                'loc' => "/blog/{$post->slug}",
                'priority' => '0.8',
                'changefreq' => 'weekly',
                'lastmod' => $post->updated_at?->toISOString(),
            ]);

        return $urls->concat($blogPosts);
    }
}
