<?php

namespace App\Filament\Resources\Settings\Schemas;

use App\Models\Setting;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Setting')
                    ->icon(Heroicon::OutlinedCog6Tooth)
                    ->description('Application configuration stored as key-value payload.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('group')
                            ->required()
                            ->maxLength(255)
                            ->helperText('e.g. general, appearance, billing'),

                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->unique(table: Setting::class, ignoreRecord: true)
                            ->helperText('Unique setting identifier, e.g. site_active'),

                        Toggle::make('locked')
                            ->label('Locked')
                            ->default(false)
                            ->helperText('Prevent editing from the POS app.'),

                        KeyValue::make('payload')
                            ->keyLabel('Key')
                            ->valueLabel('Value')
                            ->required()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
