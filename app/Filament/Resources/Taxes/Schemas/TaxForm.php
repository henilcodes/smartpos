<?php

namespace App\Filament\Resources\Taxes\Schemas;

use App\Models\Tax;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class TaxForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Tax rate')
                    ->icon(Heroicon::OutlinedReceiptPercent)
                    ->description('Define individual tax components such as CGST or SGST.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->unique(table: Tax::class, ignoreRecord: true)
                            ->columnSpanFull(),

                        Select::make('type')
                            ->options([
                                'percentage' => 'Percentage',
                                'fixed' => 'Fixed amount',
                            ])
                            ->required()
                            ->native(false),

                        TextInput::make('rate')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->helperText('Enter percentage value or fixed amount based on the selected type.'),
                    ]),
            ]);
    }
}
