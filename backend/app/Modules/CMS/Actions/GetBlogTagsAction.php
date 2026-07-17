<?php

declare(strict_types=1);

namespace App\Modules\CMS\Actions;

use App\Core\Base\Action;
use App\Modules\CMS\Models\BlogTag;
use Illuminate\Database\Eloquent\Collection;

class GetBlogTagsAction extends Action
{
    public function execute(mixed ...$params): Collection
    {
        return BlogTag::query()
            ->withCount('posts')
            ->orderBy('name')
            ->get();
    }
}
