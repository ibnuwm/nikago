<?php

declare(strict_types=1);

namespace App\Modules\System\Actions;

use App\Core\Base\Action;
use App\Modules\System\Models\Setting;
use Illuminate\Http\Request;

class UpdatePreferencesAction extends Action
{
    public function execute(mixed ...$params): array
    {
        /** @var Request $request */
        $request = $params[0];
        $data = $params[1];
        $user = $request->user();

        $allowed = ['theme', 'language', 'timezone'];
        $updated = [];

        foreach ($allowed as $key) {
            if (array_key_exists($key, $data)) {
                Setting::updateOrCreate(
                    [
                        'key' => "user_{$user->id}_{$key}",
                        'group' => 'preferences',
                    ],
                    [
                        'value' => ['value' => $data[$key]],
                        'is_public' => false,
                    ]
                );
                $updated[$key] = $data[$key];
            }
        }

        return $updated;
    }
}
