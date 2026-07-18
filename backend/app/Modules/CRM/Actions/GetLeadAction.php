<?php

declare(strict_types=1);

namespace App\Modules\CRM\Actions;

use App\Modules\CRM\Models\Lead;
use App\Modules\CRM\Resources\LeadResource;
use Illuminate\Contracts\Auth\Authenticatable;

class GetLeadAction
{
    public function execute(Authenticatable $user, string $uuid): LeadResource
    {
        $lead = Lead::query()
            ->with(['assignedTo', 'followUps', 'activities'])
            ->where('uuid', $uuid)
            ->firstOrFail();

        return new LeadResource($lead);
    }
}
