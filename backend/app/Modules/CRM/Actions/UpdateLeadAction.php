<?php

declare(strict_types=1);

namespace App\Modules\CRM\Actions;

use App\Modules\CRM\Models\Lead;
use App\Modules\CRM\Models\LeadActivity;
use App\Modules\CRM\Resources\LeadResource;
use Illuminate\Contracts\Auth\Authenticatable;

class UpdateLeadAction
{
    public function execute(Authenticatable $user, string $uuid, array $data): LeadResource
    {
        $lead = Lead::query()
            ->where('uuid', $uuid)
            ->firstOrFail();

        $oldStage = $lead->stage;
        $lead->update($data);

        if (isset($data['stage']) && $data['stage'] !== $oldStage) {
            LeadActivity::query()->create([
                'lead_id' => $lead->id,
                'type' => 'stage_changed',
                'description' => "Stage changed from {$oldStage} to {$data['stage']}.",
                'metadata' => ['from' => $oldStage, 'to' => $data['stage']],
            ]);
        }

        return new LeadResource($lead->load(['assignedTo', 'followUps', 'activities']));
    }
}
