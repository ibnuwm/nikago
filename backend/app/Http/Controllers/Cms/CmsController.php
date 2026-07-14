<?php

declare(strict_types=1);

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Modules\CMS\Actions\GetBannersAction;
use App\Modules\CMS\Actions\GetFaqsAction;
use App\Modules\CMS\Actions\GetPageBySlugAction;
use App\Modules\CMS\Actions\GetPagesAction;
use App\Modules\CMS\Resources\BannerResource;
use App\Modules\CMS\Resources\FaqResource;
use App\Modules\CMS\Resources\PageResource;
use Illuminate\Http\JsonResponse;

class CmsController extends Controller
{
    public function faqs(GetFaqsAction $action): JsonResponse
    {
        $faqs = $action->execute();

        return response()->json([
            'success' => true,
            'data' => FaqResource::collection($faqs),
        ]);
    }

    public function banners(GetBannersAction $action): JsonResponse
    {
        $banners = $action->execute();

        return response()->json([
            'success' => true,
            'data' => BannerResource::collection($banners),
        ]);
    }

    public function pages(GetPagesAction $action): JsonResponse
    {
        $pages = $action->execute();

        return response()->json([
            'success' => true,
            'data' => PageResource::collection($pages),
        ]);
    }

    public function pageBySlug(string $slug, GetPageBySlugAction $action): JsonResponse
    {
        if (! preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid page slug format.',
            ], 422);
        }

        $page = $action->execute($slug);

        if (! $page) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new PageResource($page),
        ]);
    }
}
