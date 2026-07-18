<?php

declare(strict_types=1);

namespace App\Modules\CRM\Controllers;

use App\Core\Base\Controller;
use App\Modules\CRM\Actions\AssignLeadAction;
use App\Modules\CRM\Actions\CreateFollowUpAction;
use App\Modules\CRM\Actions\CreateLeadAction;
use App\Modules\CRM\Actions\DeleteLeadAction;
use App\Modules\CRM\Actions\GetLeadAction;
use App\Modules\CRM\Actions\GetStatisticsAction;
use App\Modules\CRM\Actions\ListLeadsAction;
use App\Modules\CRM\Actions\ListPipelinesAction;
use App\Modules\CRM\Actions\MoveStageAction;
use App\Modules\CRM\Actions\UpdateLeadAction;
use App\Modules\CRM\Requests\AssignLeadRequest;
use App\Modules\CRM\Requests\MoveStageRequest;
use App\Modules\CRM\Requests\StoreFollowUpRequest;
use App\Modules\CRM\Requests\StoreLeadRequest;
use App\Modules\CRM\Requests\UpdateLeadRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CrmController extends Controller
{
    public function __construct(
        private readonly ListLeadsAction $listLeadsAction,
        private readonly CreateLeadAction $createLeadAction,
        private readonly GetLeadAction $getLeadAction,
        private readonly UpdateLeadAction $updateLeadAction,
        private readonly DeleteLeadAction $deleteLeadAction,
        private readonly AssignLeadAction $assignLeadAction,
        private readonly MoveStageAction $moveStageAction,
        private readonly CreateFollowUpAction $createFollowUpAction,
        private readonly ListPipelinesAction $listPipelinesAction,
        private readonly GetStatisticsAction $getStatisticsAction,
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        return $this->listLeadsAction->execute(
            $request->user(),
            $request->only(['per_page', 'stage', 'search', 'source'])
        );
    }

    public function store(StoreLeadRequest $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->createLeadAction->execute($request->user(), $request->validated()),
        ], 201);
    }

    public function show(Request $request, string $uuid): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->getLeadAction->execute($request->user(), $uuid),
        ]);
    }

    public function update(UpdateLeadRequest $request, string $uuid): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->updateLeadAction->execute($request->user(), $uuid, $request->validated()),
        ]);
    }

    public function destroy(string $uuid): JsonResponse
    {
        $this->deleteLeadAction->execute($uuid);

        return response()->json(['success' => true]);
    }

    public function assign(AssignLeadRequest $request, string $uuid): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->assignLeadAction->execute($request->user(), $uuid, $request->validated()),
        ]);
    }

    public function moveStage(MoveStageRequest $request, string $uuid): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->moveStageAction->execute($request->user(), $uuid, $request->validated()),
        ]);
    }

    public function followUp(StoreFollowUpRequest $request, string $uuid): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->createFollowUpAction->execute($request->user(), $uuid, $request->validated()),
        ]);
    }

    public function pipelines(Request $request): AnonymousResourceCollection
    {
        return $this->listPipelinesAction->execute($request->user());
    }

    public function statistics(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->getStatisticsAction->execute($request->user()),
        ]);
    }
}
