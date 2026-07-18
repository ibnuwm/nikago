<?php

declare(strict_types=1);

namespace App\Modules\AI\Controllers;

use App\Core\Base\Controller;
use App\Modules\AI\Actions\ChatAction;
use App\Modules\AI\Actions\GenerateContentAction;
use App\Modules\AI\Actions\GetUsageAction;
use App\Modules\AI\Actions\ListHistoryAction;
use App\Modules\AI\Actions\ListModelsAction;
use App\Modules\AI\Requests\AiChatRequest;
use App\Modules\AI\Requests\AiGenerateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AiController extends Controller
{
    public function __construct(
        private readonly ChatAction $chatAction,
        private readonly GenerateContentAction $generateContentAction,
        private readonly ListHistoryAction $listHistoryAction,
        private readonly ListModelsAction $listModelsAction,
        private readonly GetUsageAction $getUsageAction,
    ) {}

    public function chat(AiChatRequest $request): JsonResponse
    {
        $result = $this->chatAction->execute(
            $request->user(),
            $request->input('messages'),
            $request->input('model'),
            $request->input('temperature') ? (float) $request->input('temperature') : null,
        );

        return response()->json([
            'success' => true,
            'data' => [
                'content' => $result['content'],
                'model' => $result['model'],
                'prompt_tokens' => $result['prompt_tokens'],
                'completion_tokens' => $result['completion_tokens'],
                'total_tokens' => $result['total_tokens'],
            ],
        ]);
    }

    public function story(AiGenerateRequest $request): JsonResponse
    {
        return $this->generate('story', $request);
    }

    public function invitation(AiGenerateRequest $request): JsonResponse
    {
        return $this->generate('invitation', $request);
    }

    public function checklist(AiGenerateRequest $request): JsonResponse
    {
        return $this->generate('checklist', $request);
    }

    public function budget(AiGenerateRequest $request): JsonResponse
    {
        return $this->generate('budget', $request);
    }

    public function timeline(AiGenerateRequest $request): JsonResponse
    {
        return $this->generate('timeline', $request);
    }

    public function rundown(AiGenerateRequest $request): JsonResponse
    {
        return $this->generate('rundown', $request);
    }

    public function caption(AiGenerateRequest $request): JsonResponse
    {
        return $this->generate('caption', $request);
    }

    public function vendorRecommendation(AiGenerateRequest $request): JsonResponse
    {
        return $this->generate('vendor_recommendation', $request);
    }

    public function history(Request $request): AnonymousResourceCollection
    {
        return $this->listHistoryAction->execute(
            $request->user(),
            $request->only(['per_page', 'feature'])
        );
    }

    public function models(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->listModelsAction->execute(),
        ]);
    }

    public function usage(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->getUsageAction->execute($request->user()),
        ]);
    }

    private function generate(string $feature, AiGenerateRequest $request): JsonResponse
    {
        $result = $this->generateContentAction->execute(
            $request->user(),
            $feature,
            $request->input('prompt'),
            $request->input('model'),
        );

        return response()->json([
            'success' => true,
            'data' => [
                'content' => $result['content'],
                'model' => $result['model'],
                'prompt_tokens' => $result['prompt_tokens'],
                'completion_tokens' => $result['completion_tokens'],
                'total_tokens' => $result['total_tokens'],
            ],
        ]);
    }
}
