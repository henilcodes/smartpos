<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CustomerInfolist
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
                            TextEntry::make('name')
                                ->placeholder('—'),

                            TextEntry::make('phone'),

                            TextEntry::make('email')
                                ->label('Email address')
                                ->placeholder('—'),

                            TextEntry::make('birthday')
                                ->date('M j, Y')
                                ->placeholder('—'),
                        ]),

                    Grid::make()
                        ->columns(2)
                        ->schema([
                            Section::make('Preferences')
                                ->compact()
                                ->schema([
                                    IconEntry::make('preferences.email_notifications')
                                        ->label('Email notifications')
                                        ->boolean(),

                                    IconEntry::make('preferences.sms_notifications')
                                        ->label('SMS notifications')
                                        ->boolean(),

                                    IconEntry::make('preferences.whatsapp_notifications')
                                        ->label('WhatsApp notifications')
                                        ->boolean(),
                                ]),

                            TextEntry::make('gender')
                                ->formatStateUsing(fn (?string $state): string => filled($state) ? ucfirst($state) : '—'),
                        ]),

                    TextEntry::make('notes')
                        ->placeholder('—')
                        ->columnSpanFull(),
                ]),
        ];
    }

    /**
     * @return array<int, Section>
     */
    protected static function sidebarColumn(): array
    {
        return [
            Section::make()
                ->compact()
                ->schema([
                    TextEntry::make('created_at')
                        ->label('Created at')
                        ->since(),

                    TextEntry::make('updated_at')
                        ->label('Last modified at')
                        ->since(),

                    TextEntry::make('is_active')
                        ->label('Active')
                        ->badge()
                        ->formatStateUsing(fn (bool $state): string => $state ? 'Yes' : 'No')
                        ->color(fn (bool $state): string => $state ? 'success' : 'gray'),
                ]),
        ];
    }
}
