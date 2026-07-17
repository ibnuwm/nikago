<?php

declare(strict_types=1);

namespace App\Modules\Rundown\Resources;

use App\Core\Base\Resource;
use App\Modules\Rundown\Models\RundownItem;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * @property-read RundownItem $resource
 */
class RundownItemResource extends Resource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->uuid,
            'rundown_id' => $this->resource->rundown_id,
            'title' => $this->resource->title,
            'description' => $this->resource->description,
            'start_time' => $this->resource->start_time instanceof Carbon
                ? $this->resource->start_time->format('H:i')
                : null,
            'end_time' => $this->resource->end_time instanceof Carbon
                ? $this->resource->end_time->format('H:i')
                : null,
            'pic' => $this->resource->pic,
            'notes' => $this->resource->notes,
            'sort_order' => $this->resource->sort_order,
            'created_at' => $this->resource->created_at instanceof Carbon
                ? $this->resource->created_at->toIsoString()
                : null,
            'updated_at' => $this->resource->updated_at instanceof Carbon
                ? $this->resource->updated_at->toIsoString()
                : null,
        ];
    }
}
