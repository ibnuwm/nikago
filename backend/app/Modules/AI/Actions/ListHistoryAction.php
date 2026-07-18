<?php

declare(strict_types=1);

namespace App\Modules\AI\Actions;

use App\Modules\AI\Models\AiHistory;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Modules\AI\Resources\AiHistoryResource;

class ListHistoryAction
{
    public function execute(Authenticatable $user, array $params = []): AnonymousResourceCollection
    {
        $perPage = (int) ($params['per_page'] ?? 15);

        $query = AiHistory::query()
            ->where('user_id', $user->id)
            ->orderByDesc('created_at');

        if (! empty($params['feature'])) {
            $query->where('feature', $params['feature']);
        }

        return AiHistoryResource::collection($query->paginate($perPage));
    }
}
