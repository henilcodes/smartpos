<?php

namespace App\Filament\Resources\Suppliers\Schemas;

use App\Models\Supplier;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SupplierForm
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
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('phone')
                                    ->tel()
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(table: Supplier::class, ignoreRecord: true),
                            ]),

                        Grid::make()
                            ->columns(2)
                            ->schema([
                                TextInput::make('email')
                                    ->label('Email address')
                                    ->email()
                                    ->maxLength(255),

                                ToggleButtons::make('is_active')
                                    ->label('Active')
                                    ->boolean()
                                    ->default(true)
                                    ->inline()
                                    ->grouped()
                                    ->colors([
                                        1 => 'success',
                                        0 => 'gray',
                                    ]),
                            ]),

                        Textarea::make('address')
                            ->rows(3)
                            ->columnSpanFull(),

                        Textarea::make('notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
