<?php

namespace App\Filament\Forms\Components;

use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Support\Icons\Heroicon;

class KeyCombinationInput extends TextInput
{
    protected string $view = 'filament.forms.components.key-combination-input';

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label('Shortcut keys')
            ->placeholder('Click here and press your shortcut keys')
            ->helperText('Press the key combination you want to use. Modifiers such as CTRL, SHIFT, and ALT are captured automatically.')
            ->readOnly()
            ->suffixAction(
                Action::make('clearCombination')
                    ->icon(Heroicon::XMark)
                    ->tooltip('Clear shortcut')
                    ->action(function (KeyCombinationInput $component, Set $set): void {
                        $set($component->getName(), null);
                    })
                    ->visible(fn (KeyCombinationInput $component): bool => filled($component->getState())),
            );
    }
}
