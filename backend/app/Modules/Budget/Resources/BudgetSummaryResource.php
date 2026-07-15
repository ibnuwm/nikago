<?php

declare(strict_types=1);

namespace App\Modules\Budget\Resources;

use App\Core\Base\Resource;

class BudgetSummaryResource extends Resource
{
    public function toArray($request): array
    {
        return $this->resource;
    }
}
