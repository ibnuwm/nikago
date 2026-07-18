<?php

declare(strict_types=1);

namespace App\Modules\System\Actions;

use App\Core\Base\Action;
use App\Modules\System\Models\Setting;
use Illuminate\Http\Request;

class UpdateNotificationPreferencesAction extends Action
{
    public function execute(mixed ...$params): array
    {
        /** @var Request $request */
        $request = $params[0];
        $data = $params[1];
        $user = $request->user();

        $allowed = ['in_app', 'email', 'whatsapp'];
        $updated = [];

        foreach ($allowed as $channel) {
            if (array_key_exists($channel, $data)) {
                Setting::updateOrCreate(
                    [
                        'key' => "user_{$user->id}_{$channel}",
                        'group' => 'notifications',
                    ],
                    [
                        'value' => ['value' => (bool) $data[$channel]],
                        'is_public' => false,
                    ]
                );
                $updated[$channel] = (bool) $data[$channel];
            }
        }

        return $updated;
    }
}
