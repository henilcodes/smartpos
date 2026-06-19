<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class UserInfolist
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
                                TextEntry::make('name')
                                    ->placeholder('—'),

                                TextEntry::make('email')
                                    ->label('Email address')
                                    ->placeholder('—'),
                            ]),

                        Grid::make()
                            ->columns(2)
                            ->schema([
                                IconEntry::make('email_verified_at')
                                    ->label('Email verified')
                                    ->boolean(),

                                IconEntry::make('is_super_admin')
                                    ->label('Super admin')
                                    ->boolean()
                                    ->visible(fn (): bool => Auth::user()?->isSuperAdmin() ?? false),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
