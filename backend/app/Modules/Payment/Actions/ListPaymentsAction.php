<?php

declare(strict_types=1);

namespace App\Modules\Payment\Actions;

use App\Modules\Payment\Models\Payment;
use App\Modules\Payment\Resources\PaymentResource;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ListPaymentsAction
{
    public function execute(Authenticatable $user, array $params = []): AnonymousResourceCollection
    {
        $perPage = (int) ($params['per_page'] ?? 15);

        $query = Payment::query()
            ->forUser($user->id)
            ->with(['items', 'method', 'refunds'])
            ->orderByDesc('created_at');

        if (!empty($params['status'])) {
            $query->where('status', $params['status']);
        }

        return PaymentResource::collection($query->paginate($perPage));
    }
}
