<?php

namespace App\Filament\Resources\Inventories\RelationManagers;

use App\Filament\Resources\Shared\Schemas\InventoryItemForm;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InventoryItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'Items';

    public function form(Schema $schema): Schema
    {
        return InventoryItemForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sr')
                    ->label('#')
                    ->alignEnd()
                    ->sortable(),

                TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable()
                    ->weight('medium'),

                TextColumn::make('qty')
                    ->label('Qty')
                    ->alignEnd()
                    ->sortable(),

                TextColumn::make('purchase_rate')
                    ->label('Purchase')
                    ->money('INR')
                    ->alignEnd(),

                TextColumn::make('mrp')
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
            ->defaultSort('sr')
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
