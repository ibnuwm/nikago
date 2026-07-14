<?php

declare(strict_types=1);

namespace App\Core\Base;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class Resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }

    /**
     * Wrap the resource in a standard API response.
     *
     * @return array<string, mixed>
     */
    public static function wrap(mixed $resource): array
    {
        return [
            'success' => true,
            'data' => $resource,
        ];
    }
}
