<?php

namespace App\Filament\Resources\Concerns;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;

trait HasFormActionsAtTopAndBottom
{
    use AlignsFormActionsStart;

    public function content(Schema $schema): Schema
    {
        if (
            $this instanceof EditRecord
            && $this->hasCombinedRelationManagerTabsWithContent()
        ) {
            return parent::content($schema);
        }

        $components = [
            $this->getFormContentComponent(),
        ];

        if ($this instanceof EditRecord) {
            $components[] = $this->getRelationManagersContentComponent();
        }

        $components[] = $this->getBottomFormActionsContentComponent();

        return $schema->components($components);
    }

    public function getFormContentComponent(): Component
    {
        if (! $this->hasFormWrapper()) {
            return Group::make([
                EmbeddedSchema::make('form'),
                $this->getTopFormActionsContentComponent(),
            ]);
        }

        return Form::make([EmbeddedSchema::make('form')])
            ->id('form')
            ->livewireSubmitHandler($this->getSubmitFormLivewireMethodName())
            ->header([
                $this->getTopFormActionsContentComponent(),
            ]);
    }

    protected function getTopFormActionsContentComponent(): Component
    {
        return Actions::make($this->getFormActions())
            ->alignment($this->getFormActionsAlignment())
            ->fullWidth($this->hasFullWidthFormActions())
            ->key('form-actions-header');
    }

    protected function getBottomFormActionsContentComponent(): Component
    {
        return Actions::make($this->getBottomFormActions())
            ->alignment($this->getFormActionsAlignment())
            ->fullWidth($this->hasFullWidthFormActions())
            ->sticky(false)
            ->extraAttributes([
                'class' => 'mt-6 w-full border-t border-gray-200 pt-4 dark:border-white/10',
            ])
            ->key('form-actions-footer');
    }

    /**
     * @return array<Action | ActionGroup>
     */
    protected function getBottomFormActions(): array
    {
        return collect($this->getFormActions())
            ->map(function (Action | ActionGroup $action): Action | ActionGroup {
                if (! $action instanceof Action) {
                    return $action;
                }

                $footerAction = $action->name($action->getName().'Footer');

                if ($footerAction->canSubmitForm()) {
                    $footerAction->formId('form');
                }

                return $footerAction;
            })
            ->all();
    }
}
