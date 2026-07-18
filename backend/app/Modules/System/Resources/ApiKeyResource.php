<?php

declare(strict_types=1);

namespace App\Modules\System\Resources;

use App\Core\Base\Resource;
use App\Modules\System\Models\ApiKey;
use Illuminate\Http\Request;

/**
 * @property-read ApiKey $resource
 */
class ApiKeyResource extends Resource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->uuid,
            'name' => $this->resource->name,
            'last_used_at' => $this->resource->last_used_at?->toISOString(),
            'expires_at' => $this->resource->expires_at?->toISOString(),
            'created_at' => $this->resource->created_at?->toISOString(),
        ];
    }
}
