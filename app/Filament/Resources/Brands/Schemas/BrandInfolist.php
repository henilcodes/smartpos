<?php

namespace App\Filament\Resources\Brands\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BrandInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make()
                    ->schema([
                        Grid::make()
                            ->columns(3)
                            ->schema([
                                TextEntry::make('name')
                                    ->placeholder('—'),

                                TextEntry::make('slug')
                                    ->placeholder('—'),

                                TextEntry::make('website')
                                    ->url(fn (?string $state): ?string => filled($state) ? $state : null)
                                    ->openUrlInNewTab()
                                    ->placeholder('—'),
                            ]),

                        Grid::make()
                            ->columns(2)
                            ->schema([
                                IconEntry::make('is_visible')
                                    ->label('Visible to customers.')
                                    ->boolean(),

                                IconEntry::make('featured')
                                    ->boolean(),
                            ]),

                        TextEntry::make('description')
                            ->html()
                            ->placeholder('—')
                            ->columnSpanFull(),

                        ImageEntry::make('image')
                            ->label('Thumbnail')
                            ->disk('public')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
