<?php

declare(strict_types=1);

namespace App\Modules\Analytics\Actions;

use App\Modules\Authentication\Models\User;
use App\Modules\Vendor\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GetVendorAnalyticsAction
{
    public function execute(Request $request): array
    {
        $startDate = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))
            : Carbon::now()->subMonth();
        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))
            : Carbon::now();

        $total = Vendor::whereBetween('created_at', [$startDate, $endDate])->count();
        $active = Vendor::where('status', 'active')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $inactive = Vendor::where('status', 'inactive')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $verified = Vendor::whereNotNull('verified_at')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $featured = Vendor::where('featured', true)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $averageRating = Vendor::whereBetween('created_at', [$startDate, $endDate])
            ->avg('rating');

        $newVendors = $total;

        $byCity = Vendor::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('city')
            ->selectRaw('city, COUNT(*) as count')
            ->groupBy('city')
            ->orderByDesc('count')
            ->limit(10)
            ->pluck('count', 'city')
            ->toArray();

        $trend = Vendor::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();

        $userCount = User::where('status', 'active')->count();
        $vendorDensity = $userCount > 0 ? round(($total / $userCount) * 100, 2) : 0;

        return [
            'total_vendors' => $total,
            'active' => $active,
            'inactive' => $inactive,
            'verified' => $verified,
            'featured' => $featured,
            'average_rating' => $averageRating ? round((float) $averageRating, 2) : 0,
            'new_vendors' => $newVendors,
            'vendor_density' => $vendorDensity,
            'by_city' => $byCity,
            'trend' => $trend,
        ];
    }
}
