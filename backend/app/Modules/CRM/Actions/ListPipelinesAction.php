<?php

declare(strict_types=1);

namespace App\Modules\CRM\Actions;

use App\Modules\CRM\Models\Lead;
use App\Modules\CRM\Resources\PipelineResource;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class ListPipelinesAction
{
    private const STAGES = [
        ['id' => 'new', 'name' => 'new', 'label' => 'New'],
        ['id' => 'contacted', 'name' => 'contacted', 'label' => 'Contacted'],
        ['id' => 'negotiation', 'name' => 'negotiation', 'label' => 'Negotiation'],
        ['id' => 'won', 'name' => 'won', 'label' => 'Won'],
        ['id' => 'lost', 'name' => 'lost', 'label' => 'Lost'],
    ];

    public function execute(Authenticatable $user): AnonymousResourceCollection
    {
        $query = Lead::query()
            ->select(['stage', DB::raw('COUNT(*) as count'), DB::raw('COALESCE(SUM(deal_value), 0) as value')])
            ->groupBy('stage');

        if ($user->vendor_id ?? null) {
            $query->where('vendor_id', $user->vendor_id);
        } elseif ($user->tenant_id ?? null) {
            $query->where('tenant_id', $user->tenant_id);
        }

        $stageStats = $query->get()->keyBy('stage');

        $pipelines = [];

        foreach (self::STAGES as $stage) {
            $stat = $stageStats->get($stage['id']);

            $pipelines[] = [
                'id' => $stage['id'],
                'name' => $stage['name'],
                'label' => $stage['label'],
                'count' => (int) ($stat->count ?? 0),
                'value' => (float) ($stat->value ?? 0),
            ];
        }

        return PipelineResource::collection(collect($pipelines));
    }
}
