<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Actions;

use App\Modules\Vendor\Models\Vendor;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;

class DeleteVendorAction
{
    public function execute(Authenticatable $user, string $uuid): JsonResponse
    {
        $vendor = Vendor::query()->forUser($user->id)->where('uuid', $uuid)->firstOrFail();

        $vendor->delete();

        return response()->json(['success' => true]);
    }
}
