<?php

declare(strict_types=1);

namespace App\Modules\Budget\Resources;

use App\Core\Base\Resource;
use App\Modules\Budget\Models\BudgetCategory;
use Carbon\Carbon;

/**
 * @property-read BudgetCategory $resource
 */
class BudgetCategoryResource extends Resource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->uuid,
            'budget_id' => $this->resource->budget_id,
            'name' => $this->resource->name,
            'allocated_amount' => (float) $this->resource->allocated_amount,
            'sort_order' => $this->resource->sort_order,
            'spent' => (float) ($this->resource->relationLoaded('transactions')
                ? $this->resource->transactions->where('type', 'expense')->sum('amount')
                : 0
            ),
            'transactions' => BudgetTransactionResource::collection(
                $this->whenLoaded('transactions')
            ),
            'created_at' => $this->resource->created_at instanceof Carbon
                ? $this->resource->created_at->toIsoString()
                : null,
            'updated_at' => $this->resource->updated_at instanceof Carbon
                ? $this->resource->updated_at->toIsoString()
                : null,
        ];
    }
}
