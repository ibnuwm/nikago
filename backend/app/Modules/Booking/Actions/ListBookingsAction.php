<?php

declare(strict_types=1);

namespace App\Modules\Booking\Actions;

use App\Modules\Booking\Models\Booking;
use App\Modules\Booking\Resources\BookingResource;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class ListBookingsAction
{
    public function execute(Authenticatable $user, array $params = []): AnonymousResourceCollection
    {
        $perPage = min((int) ($params['per_page'] ?? 15), 100);
        $status = $params['status'] ?? null;

        $query = Booking::query()
            ->forUser($user->id)
            ->with(['vendor', 'package']);

        if ($status !== null && $status !== '') {
            $query->where('status', $status);
        }

        $query->orderBy('created_at', 'desc');

        /** @var LengthAwarePaginator $paginator */
        $paginator = $query->paginate($perPage);

        return BookingResource::collection($paginator);
    }
}
