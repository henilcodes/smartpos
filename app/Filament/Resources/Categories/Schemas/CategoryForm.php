<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
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
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, ?string $state): mixed => $set('slug', Str::slug($state ?? ''))),

                                TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(table: Category::class, ignoreRecord: true)
                                    ->disabled()
                                    ->dehydrated(),
                            ]),

                        Grid::make()
                            ->columns(2)
                            ->schema([
                                Toggle::make('is_visible')
                                    ->label('Visible to customers')
                                    ->default(true),

                                Toggle::make('featured')
                                    ->default(false),
                            ]),

                        Grid::make()
                            ->columns(3)
                            ->schema([
                                RichEditor::make('description')
                                    ->columnSpan(2),

                                FileUpload::make('image')
                                    ->label('Thumbnail')
                                    ->image()
                                    ->directory('categories')
                                    ->columnSpan(1),
                            ]),
                    ])
                    ->columnSpanFull(),

                Section::make('Assign Products')
                    ->schema([
                        Select::make('products')
                            ->label('Products')
                            ->relationship('products', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
