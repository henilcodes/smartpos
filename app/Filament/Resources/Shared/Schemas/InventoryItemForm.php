<?php

namespace App\Filament\Resources\Shared\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class InventoryItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Line item')
                    ->icon(Heroicon::OutlinedQueueList)
                    ->columns(2)
                    ->schema([
                        TextInput::make('sr')
                            ->label('Sort order')
                            ->numeric()
                            ->default(0)
                            ->minValue(0),

                        Select::make('product_id')
                            ->label('Product')
                            ->relationship('product', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->native(false),

                        TextInput::make('qty')
                            ->label('Quantity')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->required(),

                        TextInput::make('mrp')
                            ->label('MRP')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->prefix('₹'),

                        TextInput::make('purchase_rate')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->prefix('₹'),

                        TextInput::make('rate_a')
                            ->label('Rate A')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->prefix('₹'),

                        TextInput::make('rate_b')
                            ->label('Rate B')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->prefix('₹'),

                        TextInput::make('rate_c')
                            ->label('Rate C')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->prefix('₹'),

                        DatePicker::make('expiry_date')
                            ->label('Expiry date')
                            ->native(false),

                        Toggle::make('is_locked')
                            ->label('Locked')
                            ->default(false),
                    ]),
            ]);
    }
}
