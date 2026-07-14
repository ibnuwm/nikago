<?php

declare(strict_types=1);

namespace App\Modules\Invitation\Actions;

use App\Core\Base\Action;
use App\Modules\Invitation\Models\InvitationTemplate;
use App\Modules\Invitation\Models\InvitationTemplateFavorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FavoriteTemplateAction extends Action
{
    public function execute(mixed ...$params): bool
    {
        /** @var Request $request */
        $request = $params[0];
        /** @var string $uuid */
        $uuid = $params[1];

        $user = $request->user();

        /** @var InvitationTemplate|null $template */
        $template = InvitationTemplate::query()
            ->where('uuid', $uuid)
            ->first();

        if (! $template) {
            return false;
        }

        DB::transaction(function () use ($user, $template): void {
            $existing = InvitationTemplateFavorite::where('user_id', $user->id)
                ->where('template_id', $template->id)
                ->lockForUpdate()
                ->first();

            if ($existing) {
                return;
            }

            InvitationTemplateFavorite::create([
                'user_id' => $user->id,
                'template_id' => $template->id,
            ]);

            $template->increment('favorites_count');
        });

        return true;
    }
}
