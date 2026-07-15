<?php

declare(strict_types=1);

namespace App\Modules\Planner\Resources;

use App\Core\Base\Resource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlannerResource extends Resource
{
    public function toArray(Request $request): array
    {
        return [
            'wedding' => $this->resource['wedding'],
            'progress' => $this->resource['progress'],
            'summary' => $this->resource['summary'],
        ];
    }

    public function toResponse($request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->toArray($request),
        ]);
    }
}
