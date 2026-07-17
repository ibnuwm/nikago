<?php

declare(strict_types=1);

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Modules\CMS\Actions\GetBannersAction;
use App\Modules\CMS\Actions\GetBlogCategoriesAction;
use App\Modules\CMS\Actions\GetBlogPostBySlugAction;
use App\Modules\CMS\Actions\GetBlogPostsAction;
use App\Modules\CMS\Actions\GetBlogTagsAction;
use App\Modules\CMS\Actions\GetFaqsAction;
use App\Modules\CMS\Actions\GetPageBySlugAction;
use App\Modules\CMS\Actions\GetPagesAction;
use App\Modules\CMS\Actions\GetPrivacyPolicyAction;
use App\Modules\CMS\Actions\GetSitemapUrlsAction;
use App\Modules\CMS\Actions\GetTermsAction;
use App\Modules\CMS\Resources\BannerResource;
use App\Modules\CMS\Resources\BlogCategoryResource;
use App\Modules\CMS\Resources\BlogPostResource;
use App\Modules\CMS\Resources\BlogTagResource;
use App\Modules\CMS\Resources\FaqResource;
use App\Modules\CMS\Resources\PageResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

    public function terms(GetTermsAction $action): JsonResponse
    {
        $page = $action->execute();

        if (! $page) {
            return response()->json([
                'success' => false,
                'message' => 'Terms not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new PageResource($page),
        ]);
    }

    public function privacyPolicy(GetPrivacyPolicyAction $action): JsonResponse
    {
        $page = $action->execute();

        if (! $page) {
            return response()->json([
                'success' => false,
                'message' => 'Privacy policy not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new PageResource($page),
        ]);
    }

    public function blogPosts(Request $request, GetBlogPostsAction $action): JsonResponse
    {
        $posts = $action->execute($request);

        return response()->json([
            'success' => true,
            'data' => BlogPostResource::collection($posts),
            'meta' => [
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'per_page' => $posts->perPage(),
                'total' => $posts->total(),
            ],
        ]);
    }

    public function blogPostBySlug(string $slug, GetBlogPostBySlugAction $action): JsonResponse
    {
        if (! preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid blog slug format.',
            ], 422);
        }

        $post = $action->execute($slug);

        if (! $post) {
            return response()->json([
                'success' => false,
                'message' => 'Blog post not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new BlogPostResource($post),
        ]);
    }

    public function blogCategories(GetBlogCategoriesAction $action): JsonResponse
    {
        $categories = $action->execute();

        return response()->json([
            'success' => true,
            'data' => BlogCategoryResource::collection($categories),
        ]);
    }

    public function blogTags(GetBlogTagsAction $action): JsonResponse
    {
        $tags = $action->execute();

        return response()->json([
            'success' => true,
            'data' => BlogTagResource::collection($tags),
        ]);
    }

    public function sitemap(GetSitemapUrlsAction $action): JsonResponse
    {
        $urls = $action->execute();

        return response()->json([
            'success' => true,
            'data' => $urls,
        ]);
    }
}
