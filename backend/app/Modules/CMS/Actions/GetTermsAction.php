<?php

declare(strict_types=1);

namespace App\Modules\CMS\Actions;

use App\Core\Base\Action;
use App\Modules\CMS\Models\Page;

class GetTermsAction extends Action
{
    public function execute(mixed ...$params): ?Page
    {
        return Page::query()
            ->published()
            ->byType('terms')
            ->first();
    }
}
