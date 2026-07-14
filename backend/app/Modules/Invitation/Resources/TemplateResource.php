<?php

declare(strict_types=1);

namespace App\Modules\Invitation\Resources;

use App\Core\Base\Resource;
use Illuminate\Http\Request;

class TemplateResource extends Resource
{
    public function toArray(Request $request): array
    {
        $user = $request->user();

        return [
            'id' => $this->resource->uuid,
            'name' => $this->resource->name,
            'slug' => $this->resource->slug,
            'category' => $this->resource->category,
            'description' => $this->resource->description,
            'image' => $this->resource->image,
            'preview_image' => $this->resource->preview_image,
            'is_premium' => $this->resource->is_premium,
            'favorites_count' => $this->resource->favorites_count,
            'is_favorited' => $user ? $this->resource->isFavoritedBy($user->id) : false,
            'created_at' => $this->resource->created_at?->toISOString(),
            'updated_at' => $this->resource->updated_at?->toISOString(),
        ];
    }
}
