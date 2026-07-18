<?php

declare(strict_types=1);

namespace App\Modules\CRM\Actions;

use App\Modules\CRM\Models\Lead;
use App\Modules\CRM\Models\LeadActivity;
use App\Modules\CRM\Resources\LeadResource;
use Illuminate\Contracts\Auth\Authenticatable;

class AssignLeadAction
{
    public function execute(Authenticatable $user, string $uuid, array $data): LeadResource
    {
        $lead = Lead::query()
            ->where('uuid', $uuid)
            ->firstOrFail();

        $lead->update(['assigned_to' => $data['assigned_to']]);

        LeadActivity::query()->create([
            'lead_id' => $lead->id,
            'type' => 'assigned',
            'description' => "Lead assigned to user #{$data['assigned_to']}.",
            'metadata' => ['assigned_to' => $data['assigned_to']],
        ]);

        return new LeadResource($lead->load(['assignedTo', 'followUps', 'activities']));
    }
}
