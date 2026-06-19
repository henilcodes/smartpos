<?php

namespace App\Filament\Resources\Customers\Schemas;

use App\Models\Customer;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Grid::make()
                    ->columns(['default' => 1, 'lg' => 3])
                    ->schema([
                        Group::make()
                            ->schema(self::mainColumn())
                            ->columnSpan(['default' => 1, 'lg' => 2]),

                        Group::make()
                            ->schema(self::sidebarColumn())
                            ->columnSpan(['default' => 1, 'lg' => 1]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    /**
     * @return array<int, Section>
     */
    protected static function mainColumn(): array
    {
        return [
            Section::make()
                ->schema([
                    Grid::make()
                        ->columns(2)
                        ->schema([
                            TextInput::make('name')
                                ->maxLength(255),

                            TextInput::make('phone')
                                ->tel()
                                ->required()
                                ->maxLength(255)
                                ->unique(table: Customer::class, ignoreRecord: true),

                            TextInput::make('email')
                                ->label('Email address')
                                ->email()
                                ->maxLength(255),

                            DatePicker::make('birthday')
                                ->native(false),
                        ]),

                    Grid::make()
                        ->columns(2)
                        ->schema([
                            Section::make('Preferences')
                                ->schema([
                                    Toggle::make('preferences.email_notifications')
                                        ->label('Email notifications')
                                        ->default(false),

                                    Toggle::make('preferences.sms_notifications')
                                        ->label('SMS notifications')
                                        ->default(false),

                                    Toggle::make('preferences.whatsapp_notifications')
                                        ->label('WhatsApp notifications')
                                        ->default(false),
                                ])
                                ->compact(),

                            Select::make('gender')
                                ->options([
                                    'male' => 'Male',
                                    'female' => 'Female',
                                    'other' => 'Other',
                                ])
                                ->native(false),
                        ]),

                    Textarea::make('notes')
                        ->rows(4)
                        ->columnSpanFull(),
                ]),
        ];
    }

    /**
     * @return array<int, Placeholder|Section>
     */
    protected static function sidebarColumn(): array
    {
        return [
            Section::make()
                ->compact()
                ->schema([
                    Placeholder::make('created_at_display')
                        ->label('Created at')
                        ->content(fn (?Customer $record): string => $record?->created_at?->diffForHumans() ?? '—')
                        ->visible(fn (string $operation): bool => $operation !== 'create'),

                    Placeholder::make('updated_at_display')
                        ->label('Last modified at')
                        ->content(fn (?Customer $record): string => $record?->updated_at?->diffForHumans() ?? '—')
                        ->visible(fn (string $operation): bool => $operation !== 'create'),

                    ToggleButtons::make('is_active')
                        ->label('Active')
                        ->boolean(trueLabel: 'Yes', falseLabel: 'No')
                        ->default(true)
                        ->inline()
                        ->grouped()
                        ->colors([
                            1 => 'success',
                            0 => 'gray',
                        ]),
                ]),
        ];
    }
}
