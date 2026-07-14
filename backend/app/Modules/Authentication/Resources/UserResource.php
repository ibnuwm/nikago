<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Resources;

use App\Core\Base\Resource;
use App\Modules\Authentication\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * @property-read User $resource
 */
class UserResource extends Resource
{
    public function toArray(Request $request): array
    {
        /** @var User $user */
        $user = $this->resource;

        return [
            'id' => $user->uuid,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'phone' => $user->phone,
            'status' => $user->status,
            'email_verified_at' => $user->email_verified_at instanceof Carbon
                ? $user->email_verified_at->toISOString()
                : $user->email_verified_at,
            'created_at' => $user->created_at?->toISOString(),
            'updated_at' => $user->updated_at?->toISOString(),
        ];
    }
}
