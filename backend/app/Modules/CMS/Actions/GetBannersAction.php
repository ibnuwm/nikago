<?php

declare(strict_types=1);

namespace App\Modules\CMS\Actions;

use App\Core\Base\Action;
use App\Modules\CMS\Models\Banner;
use Illuminate\Database\Eloquent\Collection;

class GetBannersAction extends Action
{
    public function execute(mixed ...$params): Collection
    {
        return Banner::query()
            ->active()
            ->ordered()
            ->get();
    }
}
