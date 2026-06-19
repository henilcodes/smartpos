<?php

namespace App\Filament\Resources\TaxGroups\Schemas;

use App\Models\TaxGroup;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class TaxGroupForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Tax group')
                    ->icon(Heroicon::OutlinedCalculator)
                    ->description('Define the HSN group. Assign taxes from the Taxes subpanel after saving.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        TextInput::make('hsn')
                            ->label('HSN code')
                            ->required()
                            ->maxLength(255)
                            ->unique(table: TaxGroup::class, ignoreRecord: true),

                        Textarea::make('notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
