<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Controllers;

use App\Core\Base\Controller;
use App\Modules\Vendor\Actions\ActivateVendorAction;
use App\Modules\Vendor\Actions\CreateVendorAction;
use App\Modules\Vendor\Actions\DeactivateVendorAction;
use App\Modules\Vendor\Actions\DeleteVendorAction;
use App\Modules\Vendor\Actions\GetVendorAction;
use App\Modules\Vendor\Actions\GetVendorsAction;
use App\Modules\Vendor\Actions\GetVendorStatisticsAction;
use App\Modules\Vendor\Actions\UpdateVendorAction;
use App\Modules\Vendor\Actions\VerifyVendorAction;
use App\Modules\Vendor\Requests\StoreVendorRequest;
use App\Modules\Vendor\Requests\UpdateVendorRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function __construct(
        private readonly GetVendorsAction $getVendorsAction,
        private readonly CreateVendorAction $createVendorAction,
        private readonly GetVendorAction $getVendorAction,
        private readonly UpdateVendorAction $updateVendorAction,
        private readonly DeleteVendorAction $deleteVendorAction,
        private readonly VerifyVendorAction $verifyVendorAction,
        private readonly ActivateVendorAction $activateVendorAction,
        private readonly DeactivateVendorAction $deactivateVendorAction,
        private readonly GetVendorStatisticsAction $getVendorStatisticsAction,
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        return $this->getVendorsAction->execute(
            $request->user(),
            $request->only(['per_page', 'search', 'category', 'verified', 'min_rating', 'sort', 'direction'])
        );
    }

    public function store(StoreVendorRequest $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->createVendorAction->execute($request, $request->user()),
        ], 201);
    }

    public function show(string $uuid): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->getVendorAction->execute($uuid),
        ]);
    }

    public function update(UpdateVendorRequest $request, string $uuid): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->updateVendorAction->execute($request, $request->user(), $uuid),
        ]);
    }

    public function destroy(Request $request, string $uuid): JsonResponse
    {
        return $this->deleteVendorAction->execute($request->user(), $uuid);
    }

    public function verify(Request $request, string $uuid): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->verifyVendorAction->execute($request->user(), $uuid),
        ]);
    }

    public function activate(string $uuid): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->activateVendorAction->execute($uuid),
        ]);
    }

    public function deactivate(string $uuid): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->deactivateVendorAction->execute($uuid),
        ]);
    }

    public function statistics(string $uuid): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->getVendorStatisticsAction->execute($uuid),
        ]);
    }
}
