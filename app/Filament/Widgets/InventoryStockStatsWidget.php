<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Products\ProductResource;
use App\Filament\Widgets\Concerns\InteractsWithDashboardFilters;
use App\Services\DashboardStatsService;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InventoryStockStatsWidget extends BaseWidget
{
    use InteractsWithDashboardFilters;

    protected static ?int $sort = 3;

    protected ?string $heading = 'Inventory & stock alerts';

    protected ?string $description = 'Low stock, expiry, and stock health.';

    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $filters = $this->dashboardFilters();
        $stats = $this->statsService()->overview($filters);

        return [
            Stat::make('Low stock items', number_format($stats['low_stock_count']))
                ->description('At or below security stock')
                ->descriptionIcon(Heroicon::OutlinedExclamationTriangle)
                ->color('warning')
                ->url(ProductResource::getUrl('index')),

            Stat::make('Out of stock', number_format($stats['out_of_stock_count']))
                ->description('Products with zero qty')
                ->descriptionIcon(Heroicon::OutlinedXCircle)
                ->color('danger')
                ->url(ProductResource::getUrl('index')),

            Stat::make('Expiring soon', number_format($stats['expiring_soon_count']))
                ->description('Within ' . DashboardStatsService::NEAR_EXPIRY_DAYS . ' days')
                ->descriptionIcon(Heroicon::OutlinedClock)
                ->color('warning'),

            Stat::make('Expired', number_format($stats['expired_count']))
                ->description('Past expiry date')
                ->descriptionIcon(Heroicon::OutlinedNoSymbol)
                ->color('danger'),
        ];
    }
}
