<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Products\ProductResource;
use App\Filament\Widgets\Concerns\InteractsWithDashboardFilters;
use App\Models\Product;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LowStockProductsTable extends BaseWidget
{
    use InteractsWithDashboardFilters;

    protected static ?int $sort = 10;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Low stock products')
            ->description('Products at or below their security stock level.')
            ->query(fn (): Builder => $this->statsService()->lowStockProductsQuery($this->dashboardFilters()))
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->weight('medium'),

                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->placeholder('—'),

                TextColumn::make('qty')
                    ->label('Stock')
                    ->alignEnd()
                    ->color('danger'),

                TextColumn::make('security_stock')
                    ->label('Security stock')
                    ->alignEnd(),

                TextColumn::make('purchase_rate')
                    ->label('Purchase rate')
                    ->money('INR')
                    ->alignEnd(),
            ])
            ->recordUrl(fn (Product $record): string => ProductResource::getUrl('edit', ['record' => $record]))
            ->headerActions([
                Action::make('viewAll')
                    ->label('View all products')
                    ->url(ProductResource::getUrl('index')),
            ])
            ->striped()
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(10);
    }
}
