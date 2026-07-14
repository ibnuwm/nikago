<?php

declare(strict_types=1);

namespace App\Modules\CMS\Actions;

use App\Core\Base\Action;
use App\Modules\CMS\Models\Page;
use Illuminate\Database\Eloquent\Collection;

class GetPagesAction extends Action
{
    public function execute(mixed ...$params): Collection
    {
        return Page::query()
            ->published()
            ->get();
    }
}
