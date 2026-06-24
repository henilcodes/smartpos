<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Orders\OrderResource;
use App\Filament\Widgets\Concerns\InteractsWithDashboardFilters;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SalesStatsWidget extends BaseWidget
{
    use InteractsWithDashboardFilters;

    protected static ?int $sort = 2;

    protected ?string $heading = 'Sales performance';

    protected ?string $description = 'Revenue and order activity for the selected date range.';

    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $filters = $this->dashboardFilters();
        $stats = $this->statsService()->overview($filters);
        $ordersInRange = max(1, (int) $stats['orders_in_range']);

        return [
            Stat::make('Sales in range', $this->statsService()->formatCurrency($stats['sales_in_range']))
                ->description(number_format($stats['orders_in_range']) . ' orders in filtered period')
                ->descriptionIcon(Heroicon::OutlinedCalendarDays)
                ->color('success')
                ->url(OrderResource::getUrl('index')),

            Stat::make('Sales today', $this->statsService()->formatCurrency($stats['sales_today']))
                ->description(number_format($stats['orders_today']) . ' orders today')
                ->descriptionIcon(Heroicon::OutlinedClock)
                ->color('primary'),

            Stat::make('Avg order value', $this->statsService()->formatCurrency($stats['sales_in_range'] / $ordersInRange))
                ->description('Based on filtered date range')
                ->descriptionIcon(Heroicon::OutlinedCalculator)
                ->color('info'),
        ];
    }
}
