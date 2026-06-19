<?php

namespace App\Filament\Resources\Taxes\Tables;

use App\Models\Tax;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TaxesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->color('info')
                    ->sortable(),

                TextColumn::make('rate')
                    ->formatStateUsing(fn (Tax $record, $state): string => $record->type === 'fixed'
                        ? '₹'.number_format((float) $state, 2)
                        : number_format((float) $state, 2).'%')
                    ->alignEnd()
                    ->sortable(),

                TextColumn::make('tax_groups_count')
                    ->label('Tax groups')
                    ->counts('taxGroups')
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
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'percentage' => 'Percentage',
                        'fixed' => 'Fixed amount',
                    ]),
            ])
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
            ->emptyStateHeading('No taxes yet')
            ->emptyStateDescription('Create tax rates to use in tax groups.')
            ->emptyStateIcon(Heroicon::OutlinedReceiptPercent);
    }
}
