<?php

declare(strict_types=1);

namespace App\Modules\Dashboard\Resources;

use App\Core\Base\Resource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @property-read array{user: mixed, wedding: mixed, subscription: mixed, statistics: array, reminders: array, recent_activity: array, upcoming_events: array} $resource
 */
class DashboardResource extends Resource
{
    public function toArray(Request $request): array
    {
        return [
            'user' => $this->resource['user'],
            'wedding' => $this->resource['wedding'],
            'subscription' => $this->resource['subscription'],
            'statistics' => $this->resource['statistics'],
            'reminders' => $this->resource['reminders'],
            'recent_activity' => $this->resource['recent_activity'],
            'upcoming_events' => $this->resource['upcoming_events'],
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
