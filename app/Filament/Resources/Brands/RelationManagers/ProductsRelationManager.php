<?php

namespace App\Filament\Resources\Brands\RelationManagers;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    protected static ?string $title = 'Products';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->weight('medium'),

                TextColumn::make('barcode')
                    ->searchable(),

                TextColumn::make('mrp')
                    ->money('INR')
                    ->alignEnd(),

                TextColumn::make('qty')
                    ->label('Qty')
                    ->alignEnd(),

                TextColumn::make('is_active')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Active' : 'Inactive')
                    ->color(fn (bool $state): string => $state ? 'success' : 'danger'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->headerActions([])
            ->toolbarActions([]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }
}
