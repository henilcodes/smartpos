<?php

namespace App\Filament\Widgets\Concerns;

use App\Services\DashboardStatsService;
use App\Support\DashboardFilters;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

trait InteractsWithDashboardFilters
{
    use InteractsWithPageFilters;

    protected function dashboardFilters(): DashboardFilters
    {
        return DashboardFilters::from($this->pageFilters);
    }

    protected function statsService(): DashboardStatsService
    {
        return app(DashboardStatsService::class);
    }
}
