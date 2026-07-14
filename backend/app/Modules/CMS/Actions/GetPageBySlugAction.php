<?php

declare(strict_types=1);

namespace App\Modules\CMS\Actions;

use App\Core\Base\Action;
use App\Modules\CMS\Models\Page;

class GetPageBySlugAction extends Action
{
    public function execute(mixed ...$params): ?Page
    {
        $slug = $params[0];

        return Page::query()
            ->published()
            ->bySlug($slug)
            ->first();
    }
}
