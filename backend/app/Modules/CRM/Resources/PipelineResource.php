<?php

declare(strict_types=1);

namespace App\Modules\CRM\Resources;

use App\Core\Base\Resource;

class PipelineResource extends Resource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource['id'],
            'name' => $this->resource['name'],
            'label' => $this->resource['label'],
            'count' => $this->resource['count'] ?? 0,
            'value' => $this->resource['value'] ?? 0,
        ];
    }
}
