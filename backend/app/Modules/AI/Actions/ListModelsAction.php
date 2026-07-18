<?php

declare(strict_types=1);

namespace App\Modules\AI\Actions;

use App\Modules\AI\Services\AiProviderInterface;

class ListModelsAction
{
    public function __construct(
        private readonly AiProviderInterface $aiProvider,
    ) {}

    public function execute(): array
    {
        return $this->aiProvider->getModels();
    }
}
