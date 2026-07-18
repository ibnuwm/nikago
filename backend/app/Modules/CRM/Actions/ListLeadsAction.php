<?php

declare(strict_types=1);

namespace App\Modules\CRM\Actions;

use App\Modules\CRM\Models\Lead;
use App\Modules\CRM\Resources\LeadResource;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ListLeadsAction
{
    public function execute(Authenticatable $user, array $params = []): AnonymousResourceCollection
    {
        $perPage = (int) ($params['per_page'] ?? 15);

        $query = Lead::query()
            ->with(['assignedTo', 'followUps'])
            ->orderByDesc('created_at');

        if ($user->vendor_id ?? null) {
            $query->where('vendor_id', $user->vendor_id);
        } elseif ($user->tenant_id ?? null) {
            $query->where('tenant_id', $user->tenant_id);
        }

        if (!empty($params['stage'])) {
            $query->inStage($params['stage']);
        }

        if (!empty($params['search'])) {
            $search = $params['search'];
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if (!empty($params['source'])) {
            $query->where('source', $params['source']);
        }

        return LeadResource::collection($query->paginate($perPage));
    }
}
