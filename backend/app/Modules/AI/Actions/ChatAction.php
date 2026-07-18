<?php

declare(strict_types=1);

namespace App\Modules\AI\Actions;

use App\Modules\AI\Models\AiHistory;
use App\Modules\AI\Services\AiProviderInterface;
use Illuminate\Contracts\Auth\Authenticatable;

class ChatAction
{
    public function __construct(
        private readonly AiProviderInterface $aiProvider,
    ) {}

    public function execute(Authenticatable $user, array $messages, ?string $model = null, ?float $temperature = null): array
    {
        $model = $model ?? 'openai/gpt-4o-mini';
        $temperature = $temperature ?? 0.7;

        $lastMessage = last($messages);

        $result = $this->aiProvider->chat($messages, $model, 2048, $temperature);

        AiHistory::create([
            'user_id' => $user->id,
            'feature' => 'chat',
            'prompt' => $lastMessage['content'] ?? '',
            'response' => $result['content'],
            'model' => $result['model'],
            'prompt_tokens' => $result['prompt_tokens'],
            'completion_tokens' => $result['completion_tokens'],
        ]);

        return $result;
    }
}
