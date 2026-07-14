<?php

declare(strict_types=1);

namespace App\Modules\Invitation\Actions;

use App\Core\Base\Action;
use App\Modules\Invitation\Models\InvitationTemplate;
use Illuminate\Http\Request;

class GetTemplateAction extends Action
{
    public function execute(mixed ...$params): ?InvitationTemplate
    {
        /** @var Request $request */
        $request = $params[0];
        /** @var string $uuid */
        $uuid = $params[1];

        return InvitationTemplate::query()
            ->where('uuid', $uuid)
            ->first();
    }
}
