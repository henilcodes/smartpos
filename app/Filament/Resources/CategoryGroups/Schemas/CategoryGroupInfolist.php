<?php

namespace App\Filament\Resources\CategoryGroups\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CategoryGroupInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Category Group Details')
                    ->schema([
                        TextEntry::make('name')
                            ->placeholder('—')
                            ->columnSpanFull(),

                        Grid::make()
                            ->columns(2)
                            ->schema([
                                IconEntry::make('is_visible')
                                    ->label('Visible to customers.')
                                    ->boolean(),

                                IconEntry::make('featured')
                                    ->boolean(),
                            ]),

                        ImageEntry::make('image')
                            ->label('Thumbnail')
                            ->disk('public')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                Section::make('Assign Categories')
                    ->schema([
                        TextEntry::make('categories.name')
                            ->label('Categories')
                            ->badge()
                            ->placeholder('—')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
