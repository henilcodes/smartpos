<?php

namespace App\Filament\Resources\Suppliers\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SupplierInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make()
                    ->schema([
                        Grid::make()
                            ->columns(2)
                            ->schema([
                                TextEntry::make('name')
                                    ->placeholder('—'),

                                TextEntry::make('phone')
                                    ->placeholder('—'),
                            ]),

                        Grid::make()
                            ->columns(2)
                            ->schema([
                                TextEntry::make('email')
                                    ->label('Email address')
                                    ->placeholder('—'),

                                IconEntry::make('is_active')
                                    ->label('Active')
                                    ->boolean(),
                            ]),

                        TextEntry::make('address')
                            ->placeholder('—')
                            ->columnSpanFull(),

                        TextEntry::make('notes')
                            ->placeholder('—')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
