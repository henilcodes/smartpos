<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Support\ModuleRegistry;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\Facades\Auth;

class UserPermissionsFormSection
{
    public static function make(): Section
    {
        $moduleRows = collect(ModuleRegistry::all())
            ->map(
                fn (string $label, string $module): Grid => Grid::make()
                    ->columns(5)
                    ->schema([
                        Placeholder::make("permission_module_{$module}")
                            ->hiddenLabel()
                            ->content($label),

                        Toggle::make("permissions.{$module}.can_view")
                            ->label('View')
                            ->inline(false)
                            ->default(false),

                        Toggle::make("permissions.{$module}.can_create")
                            ->label('Create')
                            ->inline(false)
                            ->default(false),

                        Toggle::make("permissions.{$module}.can_edit")
                            ->label('Edit')
                            ->inline(false)
                            ->default(false),

                        Toggle::make("permissions.{$module}.can_delete")
                            ->label('Delete')
                            ->inline(false)
                            ->default(false),
                    ]),
            )
            ->values()
            ->all();

        return Section::make('Module permissions')
            ->description('Choose which modules this user can view, create, edit, or delete.')
            ->visible(fn (string $operation): bool => $operation === 'create' && (Auth::user()?->isSuperAdmin() ?? false))
            ->hidden(fn (Get $get): bool => (bool) $get('is_super_admin'))
            ->schema([
                Grid::make()
                    ->columns(5)
                    ->schema([
                        Placeholder::make('permissions_header_module')
                            ->hiddenLabel()
                            ->content('Module')
                            ->extraAttributes(['class' => 'font-semibold']),

                        Placeholder::make('permissions_header_view')
                            ->hiddenLabel()
                            ->content('View')
                            ->extraAttributes(['class' => 'font-semibold']),

                        Placeholder::make('permissions_header_create')
                            ->hiddenLabel()
                            ->content('Create')
                            ->extraAttributes(['class' => 'font-semibold']),

                        Placeholder::make('permissions_header_edit')
                            ->hiddenLabel()
                            ->content('Edit')
                            ->extraAttributes(['class' => 'font-semibold']),

                        Placeholder::make('permissions_header_delete')
                            ->hiddenLabel()
                            ->content('Delete')
                            ->extraAttributes(['class' => 'font-semibold']),
                    ]),

                ...$moduleRows,
            ])
            ->columnSpanFull();
    }
}
