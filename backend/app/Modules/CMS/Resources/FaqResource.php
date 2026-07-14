<?php

declare(strict_types=1);

namespace App\Modules\CMS\Resources;

use App\Core\Base\Resource;
use App\Modules\CMS\Models\Faq;
use Illuminate\Http\Request;

/**
 * @property-read Faq $resource
 */
class FaqResource extends Resource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->uuid,
            'question' => $this->resource->question,
            'answer' => $this->resource->answer,
            'category' => $this->resource->category,
            'sort_order' => $this->resource->sort_order,
            'created_at' => $this->resource->created_at?->toISOString(),
            'updated_at' => $this->resource->updated_at?->toISOString(),
        ];
    }
}
