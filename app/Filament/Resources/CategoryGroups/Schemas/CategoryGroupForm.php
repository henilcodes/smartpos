<?php

namespace App\Filament\Resources\CategoryGroups\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CategoryGroupForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Category Group Details')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Grid::make()
                            ->columns(2)
                            ->schema([
                                Toggle::make('is_visible')
                                    ->label('Visible to customers.')
                                    ->default(true),

                                Toggle::make('featured')
                                    ->default(false),
                            ]),

                        FileUpload::make('image')
                            ->label('Thumbnail')
                            ->image()
                            ->directory('category-groups')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                Section::make('Assign Categories')
                    ->schema([
                        Select::make('categories')
                            ->label('Categories')
                            ->relationship('categories', 'name')
                            ->multiple()
                            ->required()
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
