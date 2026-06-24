<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Customers\CustomerResource;
use App\Filament\Resources\Orders\OrderResource;
use App\Filament\Resources\Products\ProductResource;
use App\Filament\Resources\Suppliers\SupplierResource;
use App\Filament\Widgets\Concerns\InteractsWithDashboardFilters;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OverviewStatsWidget extends BaseWidget
{
    use InteractsWithDashboardFilters;

    protected static ?int $sort = 1;

    protected ?string $heading = 'Business overview';

    protected ?string $description = 'Key counts across inventory, sales, and parties.';

    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $filters = $this->dashboardFilters();
        $stats = $this->statsService()->overview($filters);

        return [
            Stat::make('Active products', number_format($stats['total_products']))
                ->description('Catalog items currently active')
                ->descriptionIcon(Heroicon::OutlinedCube)
                ->color('primary')
                ->url(ProductResource::getUrl('index')),

            Stat::make('Total stock qty', number_format($stats['total_stock_qty']))
                ->description('Units across all products')
                ->descriptionIcon(Heroicon::OutlinedArchiveBox)
                ->color('info'),

            Stat::make('Stock value', $this->statsService()->formatCurrency($stats['stock_value']))
                ->description('Qty × purchase rate')
                ->descriptionIcon(Heroicon::OutlinedBanknotes)
                ->color('success'),

            Stat::make('Active customers', number_format($stats['active_customers']))
                ->description('Customers marked active')
                ->descriptionIcon(Heroicon::OutlinedUsers)
                ->color('primary')
                ->url(CustomerResource::getUrl('index')),

            Stat::make('Active suppliers', number_format($stats['active_suppliers']))
                ->description('Suppliers marked active')
                ->descriptionIcon(Heroicon::OutlinedTruck)
                ->color('gray')
                ->url(SupplierResource::getUrl('index')),

            Stat::make('Total sales orders', number_format($stats['total_orders']))
                ->description(number_format($stats['orders_in_range']) . ' in filtered range')
                ->descriptionIcon(Heroicon::OutlinedShoppingCart)
                ->color('warning')
                ->url(OrderResource::getUrl('index')),
        ];
    }
}
