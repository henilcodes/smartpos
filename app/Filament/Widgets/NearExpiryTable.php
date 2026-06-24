<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\InteractsWithDashboardFilters;
use App\Services\DashboardStatsService;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Collection;

class NearExpiryTable extends BaseWidget
{
    use InteractsWithDashboardFilters;

    protected static ?int $sort = 11;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $rows = $this->statsService()->nearExpiryRows(20, $this->dashboardFilters());

        return $table
            ->heading('Near expiry stock')
            ->description('Products and inventory batches expiring within ' . DashboardStatsService::NEAR_EXPIRY_DAYS . ' days.')
            ->records(fn (): Collection => $rows)
            ->columns([
                TextColumn::make('source')
                    ->badge(),

                TextColumn::make('name')
                    ->label('Product')
                    ->weight('medium')
                    ->wrap(),

                TextColumn::make('reference')
                    ->label('Reference'),

                TextColumn::make('party')
                    ->label('Supplier')
                    ->placeholder('—'),

                TextColumn::make('qty')
                    ->label('Qty')
                    ->alignEnd()
                    ->placeholder('—'),

                TextColumn::make('expiry_date')
                    ->label('Expiry')
                    ->date()
                    ->color('warning'),
            ])
            ->striped()
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(10);
    }
}
