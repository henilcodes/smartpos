<?php

namespace App\Filament\Resources\TaxGroups;

use App\Filament\Resources\Concerns\AuthorizesModuleAccess;
use App\Filament\Resources\TaxGroups\Pages\CreateTaxGroup;
use App\Filament\Resources\TaxGroups\Pages\EditTaxGroup;
use App\Filament\Resources\TaxGroups\Pages\ListTaxGroups;
use App\Filament\Resources\TaxGroups\Pages\ViewTaxGroup;
use App\Filament\Resources\TaxGroups\RelationManagers\TaxesRelationManager;
use App\Filament\Resources\TaxGroups\Schemas\TaxGroupForm;
use App\Filament\Resources\TaxGroups\Tables\TaxGroupsTable;
use App\Models\TaxGroup;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TaxGroupResource extends Resource
{
    use AuthorizesModuleAccess;

    protected static ?string $model = TaxGroup::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalculator;

    protected static ?string $navigationLabel = 'Tax groups';

    protected static string|UnitEnum|null $navigationGroup = 'Taxation';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return TaxGroupForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TaxGroupsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            TaxesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTaxGroups::route('/'),
            'create' => CreateTaxGroup::route('/create'),
            'view' => ViewTaxGroup::route('/{record}'),
            'edit' => EditTaxGroup::route('/{record}/edit'),
        ];
    }

    protected static function getModuleKey(): string
    {
        return 'tax_groups';
    }
}
