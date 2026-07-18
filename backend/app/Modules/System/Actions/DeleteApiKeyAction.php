<?php

declare(strict_types=1);

namespace App\Modules\System\Actions;

use App\Core\Base\Action;
use App\Core\Exceptions\NotFoundException;
use App\Modules\System\Models\ApiKey;
use Illuminate\Http\Request;

class DeleteApiKeyAction extends Action
{
    public function execute(mixed ...$params): mixed
    {
        /** @var Request $request */
        $request = $params[0];
        $uuid = $params[1];
        $user = $request->user();

        $apiKey = ApiKey::where('uuid', $uuid)
            ->where('user_id', $user->id)
            ->first();

        if (! $apiKey) {
            throw new NotFoundException('API key not found.');
        }

        $apiKey->delete();

        return true;
    }
}
