<?php

declare(strict_types=1);

namespace App\Modules\Budget\Resources;

use App\Core\Base\Resource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BudgetSummaryResource extends Resource
{
    public function toArray(Request $request): array
    {
        return $this->resource;
    }

    public function toResponse($request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->toArray($request),
        ]);
    }
}
