<?php

declare(strict_types=1);

namespace App\Modules\AI\Actions;

use App\Modules\AI\Models\AiUsage;
use Illuminate\Contracts\Auth\Authenticatable;

class GetUsageAction
{
    public function execute(Authenticatable $user): array
    {
        $totals = AiUsage::where('user_id', $user->id)
            ->selectRaw('COALESCE(SUM(total_tokens), 0) as total_tokens, COALESCE(SUM(cost), 0) as total_cost, COUNT(*) as total_requests')
            ->first();

        $usageByFeature = AiUsage::where('user_id', $user->id)
            ->selectRaw('feature, SUM(total_tokens) as total_tokens, SUM(cost) as cost, COUNT(*) as requests')
            ->groupBy('feature')
            ->get()
            ->toArray();

        return [
            'total_tokens' => (int) $totals->total_tokens,
            'total_cost' => (float) $totals->total_cost,
            'total_requests' => (int) $totals->total_requests,
            'usage_by_feature' => $usageByFeature,
        ];
    }
}
