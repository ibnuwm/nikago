<?php

declare(strict_types=1);

namespace App\Modules\CMS\Actions;

use App\Core\Base\Action;
use App\Modules\CMS\Models\Faq;
use Illuminate\Database\Eloquent\Collection;

class GetFaqsAction extends Action
{
    public function execute(mixed ...$params): Collection
    {
        return Faq::query()
            ->active()
            ->ordered()
            ->get();
    }
}
