<?php

namespace App\Filament\Resources\CategoryGroups\RelationManagers;

use App\Filament\Resources\Categories\CategoryResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CategoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'categories';

    protected static ?string $title = 'Items';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('Sr')
                    ->rowIndex()
                    ->alignEnd(),

                TextColumn::make('name')
                    ->label('Category')
                    ->searchable()
                    ->weight('medium'),
            ])
            ->defaultSort('name')
            ->searchable()
            ->recordActions([
                ViewAction::make()
                    ->url(fn ($record): string => CategoryResource::getUrl('view', ['record' => $record])),

                EditAction::make()
                    ->url(fn ($record): string => CategoryResource::getUrl('edit', ['record' => $record])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No categories assigned')
            ->emptyStateDescription('Assign categories using the form above.');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }
}
