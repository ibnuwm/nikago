<?php

declare(strict_types=1);

namespace App\Modules\System\Actions;

use App\Core\Base\Action;
use App\Modules\System\Models\Setting;
use Illuminate\Http\Request;

class GetPreferencesAction extends Action
{
    public function execute(mixed ...$params): array
    {
        /** @var Request $request */
        $request = $params[0];
        $user = $request->user();

        $settings = Setting::where('group', 'preferences')
            ->where(function ($query) use ($user): void {
                $query->where('is_public', true)
                    ->orWhere('key', 'like', "user_{$user->id}_%");
            })
            ->pluck('value', 'key')
            ->toArray();

        $uid = $user->id;

        return [
            'theme' => $settings["user_{$uid}_theme"]['value'] ?? 'light',
            'language' => $settings["user_{$uid}_language"]['value'] ?? 'id',
            'timezone' => $settings["user_{$uid}_timezone"]['value'] ?? 'UTC',
        ];
    }
}
