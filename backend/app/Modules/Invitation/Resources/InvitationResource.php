<?php

declare(strict_types=1);

namespace App\Modules\Invitation\Resources;

use App\Core\Base\Resource;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InvitationResource extends Resource
{
    public function toArray(Request $request): array
    {
        $publishedAt = $this->resource->published_at;

        return [
            'id' => $this->resource->uuid,
            'title' => $this->resource->title,
            'slug' => $this->resource->slug,
            'description' => $this->resource->description,
            'cover_image' => $this->resource->cover_image,
            'status' => $this->resource->status,
            'published_at' => $publishedAt instanceof Carbon ? $publishedAt->toISOString() : null,
            'wedding_id' => $this->resource->wedding?->uuid,
            'created_at' => $this->resource->created_at?->toISOString(),
            'updated_at' => $this->resource->updated_at?->toISOString(),
        ];
    }
}
