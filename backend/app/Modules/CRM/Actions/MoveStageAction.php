<?php

declare(strict_types=1);

namespace App\Modules\CRM\Actions;

use App\Modules\CRM\Models\Lead;
use App\Modules\CRM\Models\LeadActivity;
use App\Modules\CRM\Resources\LeadResource;
use Illuminate\Contracts\Auth\Authenticatable;

class MoveStageAction
{
    public function execute(Authenticatable $user, string $uuid, array $data): LeadResource
    {
        $lead = Lead::query()
            ->where('uuid', $uuid)
            ->firstOrFail();

        $oldStage = $lead->stage;
        $newStage = $data['stage'];

        if ($oldStage === $newStage) {
            return new LeadResource($lead->load(['assignedTo', 'followUps', 'activities']));
        }

        $lead->update([
            'stage' => $newStage,
            'closed_at' => in_array($newStage, ['won', 'lost']) ? now() : null,
        ]);

        LeadActivity::query()->create([
            'lead_id' => $lead->id,
            'type' => 'stage_changed',
            'description' => "Stage moved from {$oldStage} to {$newStage}.",
            'metadata' => ['from' => $oldStage, 'to' => $newStage],
        ]);

        return new LeadResource($lead->load(['assignedTo', 'followUps', 'activities']));
    }
}
