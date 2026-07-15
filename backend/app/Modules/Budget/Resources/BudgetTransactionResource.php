<?php

declare(strict_types=1);

namespace App\Modules\Budget\Resources;

use App\Core\Base\Resource;
use App\Modules\Budget\Models\BudgetTransaction;
use Carbon\Carbon;

/**
 * @property-read BudgetTransaction $resource
 */
class BudgetTransactionResource extends Resource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->uuid,
            'category_id' => $this->resource->category_id,
            'type' => $this->resource->type,
            'amount' => (float) $this->resource->amount,
            'description' => $this->resource->description,
            'transaction_date' => $this->resource->transaction_date instanceof Carbon
                ? $this->resource->transaction_date->toDateString()
                : null,
            'created_at' => $this->resource->created_at instanceof Carbon
                ? $this->resource->created_at->toIsoString()
                : null,
            'updated_at' => $this->resource->updated_at instanceof Carbon
                ? $this->resource->updated_at->toIsoString()
                : null,
        ];
    }
}
