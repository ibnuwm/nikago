<?php

declare(strict_types=1);

namespace App\Modules\CRM\Actions;

use App\Modules\CRM\Models\Lead;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\DB;

class GetStatisticsAction
{
    public function execute(Authenticatable $user): array
    {
        $query = Lead::query()
            ->select([
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN stage = 'won' THEN 1 ELSE 0 END) as won"),
                DB::raw("SUM(CASE WHEN stage = 'lost' THEN 1 ELSE 0 END) as lost"),
                DB::raw("SUM(CASE WHEN stage IN ('new', 'contacted', 'negotiation') THEN 1 ELSE 0 END) as active"),
                DB::raw('COALESCE(SUM(deal_value), 0) as total_value'),
                DB::raw("COALESCE(SUM(CASE WHEN stage = 'won' THEN deal_value ELSE 0 END), 0) as won_value"),
            ]);

        if ($user->vendor_id ?? null) {
            $query->where('vendor_id', $user->vendor_id);
        } elseif ($user->tenant_id ?? null) {
            $query->where('tenant_id', $user->tenant_id);
        }

        $stats = $query->first();

        $total = (int) ($stats->total ?? 0);
        $won = (int) ($stats->won ?? 0);

        return [
            'total_leads' => $total,
            'won' => $won,
            'lost' => (int) ($stats->lost ?? 0),
            'active' => (int) ($stats->active ?? 0),
            'total_value' => (float) ($stats->total_value ?? 0),
            'won_value' => (float) ($stats->won_value ?? 0),
            'conversion_rate' => $total > 0 ? round(($won / $total) * 100, 2) : 0,
        ];
    }
}
