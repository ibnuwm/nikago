<?php

declare(strict_types=1);

namespace App\Modules\CRM\Actions;

use App\Modules\CRM\Models\Lead;
use App\Modules\CRM\Models\LeadActivity;
use App\Modules\CRM\Models\LeadFollowUp;
use App\Modules\CRM\Resources\LeadResource;
use Illuminate\Contracts\Auth\Authenticatable;

class CreateFollowUpAction
{
    public function execute(Authenticatable $user, string $uuid, array $data): LeadResource
    {
        $lead = Lead::query()
            ->where('uuid', $uuid)
            ->firstOrFail();

        LeadFollowUp::query()->create([
            'lead_id' => $lead->id,
            'type' => $data['type'],
            'notes' => $data['notes'],
            'follow_up_date' => $data['follow_up_date'] ?? null,
        ]);

        LeadActivity::query()->create([
            'lead_id' => $lead->id,
            'type' => 'follow_up',
            'description' => "Follow-up ({$data['type']}): {$data['notes']}",
            'metadata' => ['follow_up_type' => $data['type']],
        ]);

        return new LeadResource($lead->load(['assignedTo', 'followUps', 'activities']));
    }
}
