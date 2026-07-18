<?php

declare(strict_types=1);

namespace App\Modules\AI\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenRouterService implements AiProviderInterface
{
    private const BASE_URL = 'https://openrouter.ai/api/v1';

    public function __construct(
        private readonly string $apiKey,
        private readonly string $siteUrl = '',
        private readonly string $siteName = '',
    ) {}

    public function chat(array $messages, string $model = 'openai/gpt-4o-mini', int $maxTokens = 2048, float $temperature = 0.7): array
    {
        $response = $this->client()->post('/chat/completions', [
            'model' => $model,
            'messages' => $messages,
            'max_tokens' => $maxTokens,
            'temperature' => $temperature,
        ]);

        if ($response->failed()) {
            Log::error('OpenRouter chat failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            $response->throw();
        }

        $data = $response->json();

        return [
            'content' => $data['choices'][0]['message']['content'] ?? '',
            'model' => $data['model'] ?? $model,
            'prompt_tokens' => $data['usage']['prompt_tokens'] ?? 0,
            'completion_tokens' => $data['usage']['completion_tokens'] ?? 0,
            'total_tokens' => $data['usage']['total_tokens'] ?? 0,
        ];
    }

    public function generate(string $systemPrompt, string $userPrompt, string $model = 'openai/gpt-4o-mini', int $maxTokens = 2048, float $temperature = 0.7): array
    {
        return $this->chat([
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => $userPrompt],
        ], $model, $maxTokens, $temperature);
    }

    public function getModels(): array
    {
        $cached = Cache::get('openrouter_models');
        if ($cached !== null) {
            return $cached;
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->get(self::BASE_URL . '/models');

        if ($response->failed()) {
            Log::error('OpenRouter getModels failed', [
                'status' => $response->status(),
            ]);

            return $this->getDefaultModels();
        }

        $data = $response->json();

        $models = collect($data['data'] ?? [])
            ->filter(fn (array $model): bool => ($model['pricing']['prompt'] ?? '0') > 0)
            ->map(fn (array $model): array => [
                'id' => $model['id'],
                'name' => $model['name'] ?? $model['id'],
                'description' => $model['description'] ?? '',
                'context_length' => $model['context_length'] ?? 0,
                'pricing' => [
                    'prompt' => (float) ($model['pricing']['prompt'] ?? 0),
                    'completion' => (float) ($model['pricing']['completion'] ?? 0),
                ],
            ])
            ->values()
            ->toArray();

        Cache::put('openrouter_models', $models, 3600);

        return $models;
    }

    public function getProviderName(): string
    {
        return 'openrouter';
    }

    private function client(): PendingRequest
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])
            ->withOptions([
                'base_uri' => self::BASE_URL,
                'timeout' => 120,
            ])
            ->when(! empty($this->siteUrl), fn (PendingRequest $request) => $request->withHeader('HTTP-Referer', $this->siteUrl))
            ->when(! empty($this->siteName), fn (PendingRequest $request) => $request->withHeader('X-Title', $this->siteName));
    }

    private function getDefaultModels(): array
    {
        return [
            ['id' => 'openai/gpt-4o-mini', 'name' => 'GPT-4o Mini', 'description' => 'Fast and affordable', 'context_length' => 128000, 'pricing' => ['prompt' => 0.00000015, 'completion' => 0.0000006]],
            ['id' => 'openai/gpt-4o', 'name' => 'GPT-4o', 'description' => 'High quality', 'context_length' => 128000, 'pricing' => ['prompt' => 0.0000025, 'completion' => 0.00001]],
            ['id' => 'anthropic/claude-3.5-haiku', 'name' => 'Claude 3.5 Haiku', 'description' => 'Fast and capable', 'context_length' => 200000, 'pricing' => ['prompt' => 0.0000008, 'completion' => 0.000004]],
            ['id' => 'google/gemini-2.0-flash-exp', 'name' => 'Gemini 2.0 Flash', 'description' => 'Fast Google model', 'context_length' => 1048576, 'pricing' => ['prompt' => 0.0000001, 'completion' => 0.0000004]],
        ];
    }
}
