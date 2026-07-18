<?php

declare(strict_types=1);

namespace App\Modules\System\Actions;

use App\Core\Base\Action;
use App\Modules\System\Models\Setting;
use Illuminate\Http\Request;

class GetNotificationPreferencesAction extends Action
{
    public function execute(mixed ...$params): array
    {
        /** @var Request $request */
        $request = $params[0];
        $user = $request->user();

        $settings = Setting::where('group', 'notifications')
            ->where('key', 'like', "user_{$user->id}_%")
            ->pluck('value', 'key')
            ->toArray();

        return [
            'in_app' => $settings['user_' . $user->id . '_in_app']['value'] ?? true,
            'email' => $settings['user_' . $user->id . '_email']['value'] ?? true,
            'whatsapp' => $settings['user_' . $user->id . '_whatsapp']['value'] ?? false,
        ];
    }
}
