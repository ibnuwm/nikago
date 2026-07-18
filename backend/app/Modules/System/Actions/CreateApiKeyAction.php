<?php

declare(strict_types=1);

namespace App\Modules\System\Actions;

use App\Core\Base\Action;
use App\Modules\System\Models\ApiKey;
use App\Modules\System\Resources\ApiKeyResource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CreateApiKeyAction extends Action
{
    public function execute(mixed ...$params): array
    {
        /** @var Request $request */
        $request = $params[0];
        $data = $params[1];
        $user = $request->user();

        $plainText = Str::random(40);

        $apiKey = ApiKey::create([
            'user_id' => $user->id,
            'name' => $data['name'],
            'key' => hash('sha256', $plainText),
            'expires_at' => $data['expires_at'] ?? null,
        ]);

        $resource = new ApiKeyResource($apiKey);
        $result = $resource->resolve($request);
        $result['plain_text_key'] = $plainText;

        return $result;
    }
}
