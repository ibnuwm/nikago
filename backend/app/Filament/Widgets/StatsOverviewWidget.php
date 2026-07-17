<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Modules\Authentication\Models\User;
use App\Modules\CMS\Models\Page;
use App\Modules\System\Models\Tenant;
use App\Modules\Vendor\Models\Vendor;
use App\Modules\Wedding\Models\Wedding;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->icon('heroicon-o-users')
                ->color('primary'),
            Stat::make('Total Tenants', Tenant::count())
                ->icon('heroicon-o-building-office')
                ->color('warning'),
            Stat::make('Total Weddings', Wedding::count())
                ->icon('heroicon-o-heart')
                ->color('danger'),
            Stat::make('Total Vendors', Vendor::count())
                ->icon('heroicon-o-truck')
                ->color('success'),
            Stat::make('Total Pages', Page::count())
                ->icon('heroicon-o-document-text')
                ->color('gray'),
        ];
    }
}
