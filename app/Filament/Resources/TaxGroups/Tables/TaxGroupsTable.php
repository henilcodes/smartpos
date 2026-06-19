<?php

namespace App\Filament\Resources\TaxGroups\Tables;

use App\Models\TaxGroup;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TaxGroupsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('hsn')
                    ->label('HSN')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('taxes.name')
                    ->label('Taxes')
                    ->badge()
                    ->separator(',')
                    ->placeholder('None'),

                TextColumn::make('products_count')
                    ->label('Products')
                    ->counts('products')
                    ->alignEnd()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name')
            ->striped()
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ])
                    ->tooltip('Actions'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No tax groups yet')
            ->emptyStateDescription('Create tax groups with HSN codes for your products.')
            ->emptyStateIcon(Heroicon::OutlinedCalculator);
    }
}
