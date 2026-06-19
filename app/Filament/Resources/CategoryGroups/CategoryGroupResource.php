<?php

namespace App\Filament\Resources\CategoryGroups;

use App\Filament\Resources\CategoryGroups\Pages\CreateCategoryGroup;
use App\Filament\Resources\CategoryGroups\Pages\EditCategoryGroup;
use App\Filament\Resources\CategoryGroups\Pages\ListCategoryGroups;
use App\Filament\Resources\CategoryGroups\Pages\ViewCategoryGroup;
use App\Filament\Resources\CategoryGroups\RelationManagers\CategoriesRelationManager;
use App\Filament\Resources\CategoryGroups\Schemas\CategoryGroupForm;
use App\Filament\Resources\CategoryGroups\Schemas\CategoryGroupInfolist;
use App\Filament\Resources\CategoryGroups\Tables\CategoryGroupsTable;
use App\Filament\Resources\Concerns\AuthorizesModuleAccess;
use App\Models\CategoryGroup;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class CategoryGroupResource extends Resource
{
    use AuthorizesModuleAccess;

    protected static ?string $model = CategoryGroup::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static ?string $navigationLabel = 'Category groups';

    protected static string|UnitEnum|null $navigationGroup = 'Inventory';

    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return CategoryGroupForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CategoryGroupInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CategoryGroupsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            CategoriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCategoryGroups::route('/'),
            'create' => CreateCategoryGroup::route('/create'),
            'view' => ViewCategoryGroup::route('/{record}'),
            'edit' => EditCategoryGroup::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    protected static function getModuleKey(): string
    {
        return 'category_groups';
    }
}
