<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Inventories\InventoryResource;
use App\Models\Inventory;
use App\Filament\Widgets\Concerns\InteractsWithDashboardFilters;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class PendingSupplierBillsTable extends BaseWidget
{
    use InteractsWithDashboardFilters;

    protected static ?int $sort = 12;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $filters = $this->dashboardFilters();

        return $table
            ->heading('Supplier bills')
            ->description('Inventory receipts filtered by dashboard supplier bill status and date range.')
            ->query(fn (): Builder => $this->statsService()->pendingSupplierInventoriesQuery($filters))
            ->columns([
                TextColumn::make('code')
                    ->label('Bill #')
                    ->searchable()
                    ->weight('medium'),

                TextColumn::make('supplier.name')
                    ->label('Supplier')
                    ->searchable()
                    ->placeholder('—'),

                TextColumn::make('date')
                    ->label('Receipt date')
                    ->date()
                    ->sortable(),

                TextColumn::make('bill_amount')
                    ->label('Amount')
                    ->money('INR')
                    ->alignEnd()
                    ->state(function (Inventory $record): float {
                        return (float) $record->items->sum(
                            fn ($item): float => (float) $item->qty * (float) $item->purchase_rate,
                        );
                    }),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('notes')
                    ->limit(40)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('supplier_id')
                    ->label('Supplier')
                    ->relationship('supplier', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordUrl(fn (Inventory $record): string => InventoryResource::getUrl('edit', ['record' => $record]))
            ->headerActions([
                Action::make('viewAll')
                    ->label('View all inventories')
                    ->url(InventoryResource::getUrl('index')),
            ])
            ->striped()
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(10)
            ->poll('60s');
    }
}
