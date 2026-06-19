<?php

namespace App\Filament\Resources\Products\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InventoryItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'inventoryItems';

    protected static ?string $title = 'Stock receipts';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('inventory.code')
                    ->label('Inventory')
                    ->searchable(),

                TextColumn::make('qty')
                    ->label('Qty')
                    ->alignEnd(),

                TextColumn::make('purchase_rate')
                    ->label('Purchase rate')
                    ->money('INR')
                    ->alignEnd(),

                TextColumn::make('expiry_date')
                    ->label('Expiry')
                    ->date('d M Y')
                    ->placeholder('—'),

                TextColumn::make('is_locked')
                    ->label('Locked')
                    ->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Yes' : 'No')
                    ->color(fn (bool $state): string => $state ? 'warning' : 'gray'),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([])
            ->headerActions([])
            ->toolbarActions([]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }
}
