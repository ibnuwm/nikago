<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Modules\Invitation\Actions\FavoriteTemplateAction;
use App\Modules\Invitation\Actions\GetPremiumTemplatesAction;
use App\Modules\Invitation\Actions\GetTemplateAction;
use App\Modules\Invitation\Actions\GetTemplateCategoriesAction;
use App\Modules\Invitation\Actions\GetTemplatesAction;
use App\Modules\Invitation\Actions\UnfavoriteTemplateAction;
use App\Modules\Invitation\Actions\UseTemplateAction;
use App\Modules\Invitation\Resources\TemplateResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function index(Request $request, GetTemplatesAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $paginated = $action->execute($request);

        return response()->json([
            'success' => true,
            'data' => TemplateResource::collection($paginated->items()),
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
            ],
        ]);
    }

    public function show(Request $request, string $uuid, GetTemplateAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $template = $action->execute($request, $uuid);

        if (! $template) {
            return response()->json([
                'success' => false,
                'message' => 'Template not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new TemplateResource($template),
        ]);
    }

    public function categories(Request $request, GetTemplateCategoriesAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $categories = $action->execute($request);

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }

    public function premium(Request $request, GetPremiumTemplatesAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $paginated = $action->execute($request);

        return response()->json([
            'success' => true,
            'data' => TemplateResource::collection($paginated->items()),
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
            ],
        ]);
    }

    public function use(Request $request, string $uuid, UseTemplateAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $template = $action->execute($request, $uuid);

        if (! $template) {
            return response()->json([
                'success' => false,
                'message' => 'Template not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new TemplateResource($template),
        ]);
    }

    public function favorite(Request $request, string $uuid, FavoriteTemplateAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $result = $action->execute($request, $uuid);

        if (! $result) {
            return response()->json([
                'success' => false,
                'message' => 'Template not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Template favorited successfully.',
        ]);
    }

    public function unfavorite(Request $request, string $uuid, UnfavoriteTemplateAction $action): JsonResponse
    {
        $this->ensureUserIsActive($request);

        $result = $action->execute($request, $uuid);

        if (! $result) {
            return response()->json([
                'success' => false,
                'message' => 'Template not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Template unfavorited successfully.',
        ]);
    }
}
