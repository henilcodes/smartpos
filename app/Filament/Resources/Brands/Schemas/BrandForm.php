<?php

namespace App\Filament\Resources\Brands\Schemas;

use App\Models\Brand;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BrandForm
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
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, ?string $state): mixed => $set('slug', Str::slug($state ?? ''))),

                                TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(table: Brand::class, ignoreRecord: true)
                                    ->disabled()
                                    ->dehydrated(),

                                TextInput::make('website')
                                    ->url()
                                    ->maxLength(255),
                            ]),

                        Grid::make()
                            ->columns(2)
                            ->schema([
                                Toggle::make('is_visible')
                                    ->label('Visible to customers.')
                                    ->default(true),

                                Toggle::make('featured')
                                    ->default(false),
                            ]),

                        RichEditor::make('description')
                            ->columnSpanFull(),

                        FileUpload::make('image')
                            ->label('Thumbnail')
                            ->image()
                            ->directory('brands')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
