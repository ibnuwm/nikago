<?php

declare(strict_types=1);

namespace App\Modules\Review\Resources;

use App\Core\Base\Resource;

class ReviewImageResource extends Resource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'image_url' => $this->resource->image_url,
            'sort_order' => $this->resource->sort_order,
        ];
    }
}
