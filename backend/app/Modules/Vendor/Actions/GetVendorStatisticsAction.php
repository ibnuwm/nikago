<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Actions;

use App\Modules\Vendor\Models\Vendor;
use App\Modules\Vendor\Resources\VendorStatisticsResource;

class GetVendorStatisticsAction
{
    public function execute(string $uuid): VendorStatisticsResource
    {
        $vendor = Vendor::query()
            ->with(['services', 'packages', 'portfolios', 'galleries', 'teams', 'documents'])
            ->where('uuid', $uuid)
            ->firstOrFail();

        $totalServices = $vendor->services->count();
        $totalPackages = $vendor->packages->count();
        $totalPortfolios = $vendor->portfolios->count();
        $totalGalleries = $vendor->galleries->count();
        $totalTeams = $vendor->teams->count();
        $totalDocuments = $vendor->documents->count();

        $averageServicePrice = $totalServices > 0
            ? $vendor->services->where('starting_price', '!==', null)->avg('starting_price')
            : null;

        return new VendorStatisticsResource([
            'total_services' => $totalServices,
            'total_packages' => $totalPackages,
            'total_portfolios' => $totalPortfolios,
            'total_galleries' => $totalGalleries,
            'total_teams' => $totalTeams,
            'total_documents' => $totalDocuments,
            'average_service_price' => $averageServicePrice !== null
                ? round((float) $averageServicePrice, 2)
                : null,
            'rating' => (float) $vendor->rating,
            'total_review' => $vendor->total_review,
            'verified' => $vendor->verified_at !== null,
        ]);
    }
}
