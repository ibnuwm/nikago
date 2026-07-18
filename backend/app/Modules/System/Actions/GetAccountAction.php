<?php

declare(strict_types=1);

namespace App\Modules\System\Actions;

use App\Core\Base\Action;
use App\Modules\System\Models\Setting;
use Illuminate\Http\Request;

class GetAccountAction extends Action
{
    public function execute(mixed ...$params): array
    {
        /** @var Request $request */
        $request = $params[0];
        $user = $request->user();

        $settings = Setting::where('group', 'account')
            ->where(function ($query) use ($user): void {
                $query->where('is_public', true)
                    ->orWhere('key', 'like', "user_{$user->id}_%");
            })
            ->pluck('value', 'key')
            ->toArray();

        $uid = $user->id;

        return [
            'timezone' => $settings["user_{$uid}_timezone"]['value'] ?? 'UTC',
            'language' => $settings["user_{$uid}_language"]['value'] ?? 'id',
            'member_since' => $user->created_at?->toISOString(),
            'email_verified' => $user->hasVerifiedEmail(),
        ];
    }
}
