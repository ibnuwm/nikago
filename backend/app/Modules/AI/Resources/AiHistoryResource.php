<?php

declare(strict_types=1);

namespace App\Modules\AI\Resources;

use App\Core\Base\Resource;
use Carbon\Carbon;

class AiHistoryResource extends Resource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->uuid,
            'feature' => $this->resource->feature,
            'prompt' => $this->resource->prompt,
            'response' => $this->resource->response,
            'model' => $this->resource->model,
            'prompt_tokens' => $this->resource->prompt_tokens,
            'completion_tokens' => $this->resource->completion_tokens,
            'created_at' => $this->resource->created_at instanceof Carbon
                ? $this->resource->created_at->toIsoString()
                : null,
        ];
    }
}
