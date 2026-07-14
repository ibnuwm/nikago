<?php

declare(strict_types=1);

namespace App\Modules\Invitation\Actions;

use App\Core\Base\Action;
use App\Modules\Invitation\Models\InvitationTemplate;
use App\Modules\Invitation\Models\InvitationTemplateFavorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnfavoriteTemplateAction extends Action
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
            $deleted = InvitationTemplateFavorite::where('user_id', $user->id)
                ->where('template_id', $template->id)
                ->lockForUpdate()
                ->delete();

            if ($deleted) {
                $template->update([
                    'favorites_count' => max(0, $template->favorites_count - 1),
                ]);
            }
        });

        return true;
    }
}
