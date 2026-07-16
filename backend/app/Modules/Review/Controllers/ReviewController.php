<?php

declare(strict_types=1);

namespace App\Modules\Review\Controllers;

use App\Core\Base\Controller;
use App\Modules\Review\Actions\CreateReviewAction;
use App\Modules\Review\Actions\DeleteReviewAction;
use App\Modules\Review\Actions\GetReviewAction;
use App\Modules\Review\Actions\ListReviewsAction;
use App\Modules\Review\Actions\ListVendorReviewsAction;
use App\Modules\Review\Actions\ReplyToReviewAction;
use App\Modules\Review\Actions\ReportReviewAction;
use App\Modules\Review\Actions\UpdateReviewAction;
use App\Modules\Review\Requests\ReplyReviewRequest;
use App\Modules\Review\Requests\ReportReviewRequest;
use App\Modules\Review\Requests\StoreReviewRequest;
use App\Modules\Review\Requests\UpdateReviewRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ReviewController extends Controller
{
    public function __construct(
        private readonly CreateReviewAction $createReviewAction,
        private readonly ListReviewsAction $listReviewsAction,
        private readonly ListVendorReviewsAction $listVendorReviewsAction,
        private readonly GetReviewAction $getReviewAction,
        private readonly UpdateReviewAction $updateReviewAction,
        private readonly DeleteReviewAction $deleteReviewAction,
        private readonly ReplyToReviewAction $replyToReviewAction,
        private readonly ReportReviewAction $reportReviewAction,
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        return $this->listReviewsAction->execute(
            $request->user(),
            $request->only(['per_page'])
        );
    }

    public function store(StoreReviewRequest $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->createReviewAction->execute(
                $request->user(),
                $request->validated()
            ),
        ], 201);
    }

    public function show(string $uuid): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->getReviewAction->execute($uuid),
        ]);
    }

    public function update(UpdateReviewRequest $request, string $uuid): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->updateReviewAction->execute(
                $request->user(),
                $uuid,
                $request->validated()
            ),
        ]);
    }

    public function destroy(Request $request, string $uuid): JsonResponse
    {
        return $this->deleteReviewAction->execute($request->user(), $uuid);
    }

    public function reply(ReplyReviewRequest $request, string $uuid): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->replyToReviewAction->execute(
                $request->user(),
                $uuid,
                $request->validated()
            ),
        ]);
    }

    public function report(ReportReviewRequest $request, string $uuid): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->reportReviewAction->execute(
                $request->user(),
                $uuid,
                $request->validated()
            ),
        ], 201);
    }

    public function vendorReviews(string $vendorUuid, Request $request): AnonymousResourceCollection
    {
        return $this->listVendorReviewsAction->execute(
            $vendorUuid,
            $request->only(['per_page'])
        );
    }
}
