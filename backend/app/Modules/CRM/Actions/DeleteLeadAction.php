<?php

declare(strict_types=1);

namespace App\Modules\CRM\Actions;

use App\Modules\CRM\Models\Lead;

class DeleteLeadAction
{
    public function execute(string $uuid): void
    {
        Lead::query()
            ->where('uuid', $uuid)
            ->firstOrFail()
            ->delete();
    }
}
