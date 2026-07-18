<?php

declare(strict_types=1);

namespace App\Modules\Subscription\Http\Controllers;

use App\Core\Base\Controller;
use App\Modules\Subscription\Actions\CancelSubscriptionAction;
use App\Modules\Subscription\Actions\DowngradeSubscriptionAction;
use App\Modules\Subscription\Actions\GetCurrentSubscriptionAction;
use App\Modules\Subscription\Actions\GetFeaturesAction;
use App\Modules\Subscription\Actions\GetSubscriptionHistoryAction;
use App\Modules\Subscription\Actions\ListPlansAction;
use App\Modules\Subscription\Actions\SubscribeAction;
use App\Modules\Subscription\Actions\UpgradeSubscriptionAction;
use App\Modules\Subscription\Requests\CancelSubscriptionRequest;
use App\Modules\Subscription\Requests\SubscribeRequest;
use App\Modules\Subscription\Requests\UpgradeSubscriptionRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(ListPlansAction $action): JsonResponse
    {
        return $this->successResponse($action->execute());
    }

    public function current(GetCurrentSubscriptionAction $action): JsonResponse
    {
        return $this->successResponse($action->execute(auth()->user()));
    }

    public function subscribe(SubscribeRequest $request, SubscribeAction $action): JsonResponse
    {
        $result = $action->execute(auth()->user(), $request->validated());

        return $this->successResponse($result, 201);
    }

    public function upgrade(UpgradeSubscriptionRequest $request, UpgradeSubscriptionAction $action): JsonResponse
    {
        return $this->successResponse($action->execute(auth()->user(), $request->validated()));
    }

    public function downgrade(UpgradeSubscriptionRequest $request, DowngradeSubscriptionAction $action): JsonResponse
    {
        return $this->successResponse($action->execute(auth()->user(), $request->validated()));
    }

    public function cancel(CancelSubscriptionRequest $request, CancelSubscriptionAction $action): JsonResponse
    {
        return $this->successResponse($action->execute(auth()->user(), $request->validated()));
    }

    public function history(GetSubscriptionHistoryAction $action): JsonResponse
    {
        return $this->successResponse($action->execute(auth()->user()));
    }

    public function features(GetFeaturesAction $action): JsonResponse
    {
        return $this->successResponse($action->execute(auth()->user()));
    }
}
