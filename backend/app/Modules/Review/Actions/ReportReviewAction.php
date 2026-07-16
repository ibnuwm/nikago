<?php

declare(strict_types=1);

namespace App\Modules\Review\Actions;

use App\Modules\Review\Models\Review;
use App\Modules\Review\Models\ReviewReport;
use App\Modules\Review\Resources\ReviewReportResource;
use Illuminate\Contracts\Auth\Authenticatable;

class ReportReviewAction
{
    public function execute(Authenticatable $user, string $uuid, array $data): ReviewReportResource
    {
        $review = Review::query()
            ->where('uuid', $uuid)
            ->firstOrFail();

        $report = ReviewReport::query()->create([
            'review_id' => $review->id,
            'user_id' => $user->id,
            'reason' => $data['reason'],
        ]);

        return new ReviewReportResource($report);
    }
}
