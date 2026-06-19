<?php

namespace App\Filament\Resources\CategoryGroups\Pages;

use App\Filament\Resources\CategoryGroups\CategoryGroupResource;
use App\Filament\Resources\Concerns\AlignsFormActionsStart;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditCategoryGroup extends EditRecord
{
    use AlignsFormActionsStart;

    protected static string $resource = CategoryGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
                ->tooltip('Actions'),
        ];
    }
}
