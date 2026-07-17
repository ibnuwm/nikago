<?php

declare(strict_types=1);

namespace App\Modules\CMS\Actions;

use App\Core\Base\Action;
use App\Modules\CMS\Models\BlogPost;

class GetBlogPostBySlugAction extends Action
{
    public function execute(mixed ...$params): ?BlogPost
    {
        $slug = $params[0] ?? null;

        return BlogPost::query()
            ->published()
            ->with(['author', 'category', 'tags'])
            ->where('slug', $slug)
            ->first();
    }
}
