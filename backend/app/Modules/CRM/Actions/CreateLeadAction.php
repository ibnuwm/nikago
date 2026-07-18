<?php

declare(strict_types=1);

namespace App\Modules\CRM\Actions;

use App\Modules\CRM\Models\Lead;
use App\Modules\CRM\Models\LeadActivity;
use App\Modules\CRM\Resources\LeadResource;
use Illuminate\Contracts\Auth\Authenticatable;

class CreateLeadAction
{
    public function execute(Authenticatable $user, array $data): LeadResource
    {
        $lead = Lead::query()->create([
            'tenant_id' => $user->tenant_id ?? 1,
            'vendor_id' => $user->vendor_id ?? null,
            'user_id' => $user->id,
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'source' => $data['source'] ?? null,
            'deal_value' => $data['deal_value'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);

        LeadActivity::query()->create([
            'lead_id' => $lead->id,
            'type' => 'created',
            'description' => "Lead {$lead->name} created.",
        ]);

        return new LeadResource($lead->load(['assignedTo', 'followUps', 'activities']));
    }
}
