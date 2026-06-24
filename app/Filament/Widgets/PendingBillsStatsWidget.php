<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Inventories\InventoryResource;
use App\Filament\Resources\Orders\OrderResource;
use App\Filament\Widgets\Concerns\InteractsWithDashboardFilters;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PendingBillsStatsWidget extends BaseWidget
{
    use InteractsWithDashboardFilters;

    protected static ?int $sort = 4;

    protected ?string $heading = 'Bills & payments summary';

    protected ?string $description = 'Supplier receipts and customer payments for the filtered period.';

    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $filters = $this->dashboardFilters();
        $stats = $this->statsService()->overview($filters);

        return [
            Stat::make('Supplier bills', number_format($stats['pending_supplier_bills']))
                ->description($this->statsService()->formatCurrency($stats['pending_supplier_bill_amount']) . ' total value')
                ->descriptionIcon(Heroicon::OutlinedDocumentText)
                ->color('warning')
                ->url(InventoryResource::getUrl('index')),

            Stat::make('Customer payments', number_format($stats['pending_customer_payments']))
                ->description($this->statsService()->formatCurrency($stats['pending_customer_payment_amount']) . ' outstanding')
                ->descriptionIcon(Heroicon::OutlinedCreditCard)
                ->color('danger')
                ->url(OrderResource::getUrl('index')),

            Stat::make('Completed bills', number_format(
                $this->statsService()->inventoriesQuery($filters->withInventoryStatus('completed'))->count(),
            ))
                ->description('Supplier receipts marked completed')
                ->descriptionIcon(Heroicon::OutlinedCheckCircle)
                ->color('success')
                ->url(InventoryResource::getUrl('index')),
        ];
    }
}
