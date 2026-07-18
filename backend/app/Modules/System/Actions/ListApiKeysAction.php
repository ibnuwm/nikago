<?php

declare(strict_types=1);

namespace App\Modules\System\Actions;

use App\Core\Base\Action;
use App\Modules\System\Models\ApiKey;
use App\Modules\System\Resources\ApiKeyResource;
use Illuminate\Http\Request;

class ListApiKeysAction extends Action
{
    public function execute(mixed ...$params): array
    {
        /** @var Request $request */
        $request = $params[0];
        $user = $request->user();

        $keys = ApiKey::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
           ->get();

        return ApiKeyResource::collection($keys)->resolve($request);
    }
}
