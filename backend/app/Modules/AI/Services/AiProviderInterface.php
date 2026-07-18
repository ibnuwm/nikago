<?php

declare(strict_types=1);

namespace App\Modules\AI\Services;

interface AiProviderInterface
{
    public function chat(array $messages, string $model = 'openai/gpt-4o-mini', int $maxTokens = 2048, float $temperature = 0.7): array;

    public function generate(string $systemPrompt, string $userPrompt, string $model = 'openai/gpt-4o-mini', int $maxTokens = 2048, float $temperature = 0.7): array;

    public function getModels(): array;

    public function getProviderName(): string;
}
