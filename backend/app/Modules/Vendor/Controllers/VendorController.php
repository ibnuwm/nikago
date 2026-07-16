<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Controllers;

use App\Core\Base\Controller;
use App\Modules\Vendor\Actions\ActivateVendorAction;
use App\Modules\Vendor\Actions\CreateVendorAction;
use App\Modules\Vendor\Actions\CreateVendorGalleryAction;
use App\Modules\Vendor\Actions\CreateVendorPackageAction;
use App\Modules\Vendor\Actions\CreateVendorPortfolioAction;
use App\Modules\Vendor\Actions\CreateVendorServiceAction;
use App\Modules\Vendor\Actions\DeactivateVendorAction;
use App\Modules\Vendor\Actions\DeleteVendorAction;
use App\Modules\Vendor\Actions\DeleteVendorGalleryAction;
use App\Modules\Vendor\Actions\DeleteVendorPackageAction;
use App\Modules\Vendor\Actions\DeleteVendorPortfolioAction;
use App\Modules\Vendor\Actions\DeleteVendorServiceAction;
use App\Modules\Vendor\Actions\GetVendorAction;
use App\Modules\Vendor\Actions\GetVendorsAction;
use App\Modules\Vendor\Actions\GetVendorGalleriesAction;
use App\Modules\Vendor\Actions\GetVendorPackagesAction;
use App\Modules\Vendor\Actions\GetVendorPortfoliosAction;
use App\Modules\Vendor\Actions\GetVendorServicesAction;
use App\Modules\Vendor\Actions\GetVendorStatisticsAction;
use App\Modules\Vendor\Actions\UpdateVendorAction;
use App\Modules\Vendor\Actions\UpdateVendorGalleryAction;
use App\Modules\Vendor\Actions\UpdateVendorPackageAction;
use App\Modules\Vendor\Actions\UpdateVendorPortfolioAction;
use App\Modules\Vendor\Actions\UpdateVendorServiceAction;
use App\Modules\Vendor\Actions\VerifyVendorAction;
use App\Modules\Vendor\Requests\StoreVendorGalleryRequest;
use App\Modules\Vendor\Requests\StoreVendorPackageRequest;
use App\Modules\Vendor\Requests\StoreVendorPortfolioRequest;
use App\Modules\Vendor\Requests\StoreVendorRequest;
use App\Modules\Vendor\Requests\StoreVendorServiceRequest;
use App\Modules\Vendor\Requests\UpdateVendorGalleryRequest;
use App\Modules\Vendor\Requests\UpdateVendorPackageRequest;
use App\Modules\Vendor\Requests\UpdateVendorPortfolioRequest;
use App\Modules\Vendor\Requests\UpdateVendorRequest;
use App\Modules\Vendor\Requests\UpdateVendorServiceRequest;
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
        private readonly GetVendorGalleriesAction $getVendorGalleriesAction,
        private readonly CreateVendorGalleryAction $createVendorGalleryAction,
        private readonly UpdateVendorGalleryAction $updateVendorGalleryAction,
        private readonly DeleteVendorGalleryAction $deleteVendorGalleryAction,
        private readonly GetVendorPortfoliosAction $getVendorPortfoliosAction,
        private readonly CreateVendorPortfolioAction $createVendorPortfolioAction,
        private readonly UpdateVendorPortfolioAction $updateVendorPortfolioAction,
        private readonly DeleteVendorPortfolioAction $deleteVendorPortfolioAction,
        private readonly GetVendorPackagesAction $getVendorPackagesAction,
        private readonly CreateVendorPackageAction $createVendorPackageAction,
        private readonly UpdateVendorPackageAction $updateVendorPackageAction,
        private readonly DeleteVendorPackageAction $deleteVendorPackageAction,
        private readonly GetVendorServicesAction $getVendorServicesAction,
        private readonly CreateVendorServiceAction $createVendorServiceAction,
        private readonly UpdateVendorServiceAction $updateVendorServiceAction,
        private readonly DeleteVendorServiceAction $deleteVendorServiceAction,
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

    public function indexGalleries(string $vendorUuid): AnonymousResourceCollection
    {
        return $this->getVendorGalleriesAction->execute($vendorUuid);
    }

    public function storeGallery(StoreVendorGalleryRequest $request, string $vendorUuid): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->createVendorGalleryAction->execute($request, $request->user(), $vendorUuid),
        ], 201);
    }

    public function updateGallery(UpdateVendorGalleryRequest $request, string $vendorUuid, int $galleryId): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->updateVendorGalleryAction->execute($request, $request->user(), $vendorUuid, $galleryId),
        ]);
    }

    public function destroyGallery(Request $request, string $vendorUuid, int $galleryId): JsonResponse
    {
        return $this->deleteVendorGalleryAction->execute($request->user(), $vendorUuid, $galleryId);
    }

    public function indexPortfolios(string $vendorUuid): AnonymousResourceCollection
    {
        return $this->getVendorPortfoliosAction->execute($vendorUuid);
    }

    public function storePortfolio(StoreVendorPortfolioRequest $request, string $vendorUuid): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->createVendorPortfolioAction->execute($request, $request->user(), $vendorUuid),
        ], 201);
    }

    public function updatePortfolio(UpdateVendorPortfolioRequest $request, string $vendorUuid, int $portfolioId): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->updateVendorPortfolioAction->execute($request, $request->user(), $vendorUuid, $portfolioId),
        ]);
    }

    public function destroyPortfolio(Request $request, string $vendorUuid, int $portfolioId): JsonResponse
    {
        return $this->deleteVendorPortfolioAction->execute($request->user(), $vendorUuid, $portfolioId);
    }

    public function indexPackages(string $vendorUuid): AnonymousResourceCollection
    {
        return $this->getVendorPackagesAction->execute($vendorUuid);
    }

    public function storePackage(StoreVendorPackageRequest $request, string $vendorUuid): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->createVendorPackageAction->execute($request, $request->user(), $vendorUuid),
        ], 201);
    }

    public function updatePackage(UpdateVendorPackageRequest $request, string $vendorUuid, int $packageId): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->updateVendorPackageAction->execute($request, $request->user(), $vendorUuid, $packageId),
        ]);
    }

    public function destroyPackage(Request $request, string $vendorUuid, int $packageId): JsonResponse
    {
        return $this->deleteVendorPackageAction->execute($request->user(), $vendorUuid, $packageId);
    }

    public function indexServices(string $vendorUuid): AnonymousResourceCollection
    {
        return $this->getVendorServicesAction->execute($vendorUuid);
    }

    public function storeService(StoreVendorServiceRequest $request, string $vendorUuid): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->createVendorServiceAction->execute($request, $request->user(), $vendorUuid),
        ], 201);
    }

    public function updateService(UpdateVendorServiceRequest $request, string $vendorUuid, int $serviceId): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->updateVendorServiceAction->execute($request, $request->user(), $vendorUuid, $serviceId),
        ]);
    }

    public function destroyService(Request $request, string $vendorUuid, int $serviceId): JsonResponse
    {
        return $this->deleteVendorServiceAction->execute($request->user(), $vendorUuid, $serviceId);
    }
}
