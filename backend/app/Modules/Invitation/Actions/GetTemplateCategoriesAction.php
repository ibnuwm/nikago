<?php

declare(strict_types=1);

namespace App\Modules\Invitation\Actions;

use App\Core\Base\Action;

class GetTemplateCategoriesAction extends Action
{
    public function execute(mixed ...$params): array
    {
        return ['general', 'modern', 'traditional', 'minimalist', 'elegant'];
    }
}
