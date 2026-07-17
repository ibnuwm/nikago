<?php

declare(strict_types=1);

namespace App\Modules\CMS\Actions;

use App\Core\Base\Action;
use App\Modules\CMS\Models\BlogPost;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class GetBlogPostsAction extends Action
{
    public function execute(mixed ...$params): LengthAwarePaginator
    {
        $request = $params[0] ?? null;

        $query = BlogPost::query()
            ->published()
            ->with(['author', 'category', 'tags']);

        if ($request instanceof Request) {
            if ($search = $request->query('search')) {
                $query->search($search);
            }

            if ($category = $request->query('category')) {
                $query->byCategory($category);
            }

            if ($tag = $request->query('tag')) {
                $query->byTag($tag);
            }

            $sortField = $this->getSortField($request, ['title', 'published_at', 'created_at'], 'published_at');
            $sortDirection = $this->getSortDirection($request, 'desc');

            $query->orderBy($sortField, $sortDirection);

            $perPage = min((int) $request->query('per_page', 12), 50);
        } else {
            $query->orderBy('published_at', 'desc');
            $perPage = 12;
        }

        return $query->paginate($perPage);
    }
}
