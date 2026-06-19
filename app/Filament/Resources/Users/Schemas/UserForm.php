<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class UserForm
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
                                    ->maxLength(255),

                                TextInput::make('email')
                                    ->label('Email address')
                                    ->email()
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(table: User::class, ignoreRecord: true),
                            ]),

                        Grid::make()
                            ->columns(2)
                            ->schema([
                                ToggleButtons::make('is_verified')
                                    ->label('Email verified')
                                    ->boolean()
                                    ->default(false)
                                    ->inline()
                                    ->grouped()
                                    ->colors([
                                        1 => 'success',
                                        0 => 'gray',
                                    ]),

                                ToggleButtons::make('is_super_admin')
                                    ->label('Super admin')
                                    ->boolean()
                                    ->default(false)
                                    ->inline()
                                    ->grouped()
                                    ->colors([
                                        1 => 'success',
                                        0 => 'gray',
                                    ])
                                    ->visible(fn (): bool => Auth::user()?->isSuperAdmin() ?? false)
                                    ->disabled(fn (string $operation, ?User $record): bool => $operation !== 'create' && $record?->is(Auth::user())),
                            ]),

                        Grid::make()
                            ->columns(2)
                            ->schema([
                                TextInput::make('password')
                                    ->password()
                                    ->revealable()
                                    ->rule(Password::default())
                                    ->required(fn (string $operation): bool => $operation === 'create')
                                    ->dehydrated(fn (?string $state): bool => filled($state))
                                    ->same('passwordConfirmation'),

                                TextInput::make('passwordConfirmation')
                                    ->label('Confirm password')
                                    ->password()
                                    ->revealable()
                                    ->required(fn (Get $get, string $operation): bool => $operation === 'create' || filled($get('password')))
                                    ->dehydrated(false),
                            ])
                            ->hidden(fn (string $operation): bool => $operation === 'view'),
                    ])
                    ->columnSpanFull(),

                UserPermissionsFormSection::make(),
            ]);
    }
}
