<?php

declare(strict_types=1);

namespace App\Modules\Budget\Resources;

use App\Core\Base\Resource;
use App\Modules\Budget\Models\Budget;
use Carbon\Carbon;

/**
 * @property-read Budget $resource
 */
class BudgetResource extends Resource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->uuid,
            'wedding_id' => $this->resource->wedding_id,
            'title' => $this->resource->title,
            'description' => $this->resource->description,
            'total_budget' => (float) $this->resource->total_budget,
            'categories' => BudgetCategoryResource::collection(
                $this->whenLoaded('categories')
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
