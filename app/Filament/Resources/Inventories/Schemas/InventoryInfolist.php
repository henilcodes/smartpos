<?php

namespace App\Filament\Resources\Inventories\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;

class InventoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Details')
                    ->schema([
                        Grid::make()
                            ->columns(3)
                            ->schema([
                                TextEntry::make('supplier.name')
                                    ->label('Supplier')
                                    ->placeholder('—'),

                                TextEntry::make('date')
                                    ->dateTime('M j, Y H:i:s'),

                                TextEntry::make('status')
                                    ->label('Payment Status')
                                    ->badge()
                                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                                    ->color(fn (string $state): string => match ($state) {
                                        'completed' => 'success',
                                        'cancelled' => 'danger',
                                        default => 'warning',
                                    }),
                            ]),
                    ]),

                Section::make('Items Details')
                    ->schema([
                        RepeatableEntry::make('items')
                            ->label('Items')
                            ->schema([
                                Grid::make()
                                    ->columns(6)
                                    ->schema([
                                        TextEntry::make('product.name')
                                            ->label('Product')
                                            ->placeholder('—'),

                                        TextEntry::make('qty')
                                            ->label('Available Stock')
                                            ->placeholder('—'),

                                        TextEntry::make('purchase_rate')
                                            ->label('Cost price')
                                            ->money('INR')
                                            ->placeholder('—'),

                                        TextEntry::make('rate_a')
                                            ->label('Sale Price')
                                            ->money('INR')
                                            ->placeholder('—'),

                                        TextEntry::make('mrp')
                                            ->label('Old Price')
                                            ->money('INR')
                                            ->placeholder('—'),

                                        TextEntry::make('expiry_date')
                                            ->label('Expiry date')
                                            ->date('M j, Y')
                                            ->placeholder('—'),
                                    ]),
                            ])
                            ->contained()
                            ->columnSpanFull(),
                    ]),

                Section::make('Other Details')
                    ->collapsible()
                    ->schema([
                        TextEntry::make('file')
                            ->label('File')
                            ->formatStateUsing(fn (?string $state): string => filled($state) ? basename($state) : '—')
                            ->url(fn (?string $state): ?string => filled($state) ? Storage::disk('public')->url($state) : null)
                            ->openUrlInNewTab()
                            ->placeholder('—')
                            ->columnSpanFull(),

                        TextEntry::make('notes')
                            ->placeholder('—')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
