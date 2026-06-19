<?php

namespace App\Filament\Resources\CategoryGroups\Pages;

use App\Filament\Resources\CategoryGroups\CategoryGroupResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCategoryGroup extends ViewRecord
{
    protected static string $resource = CategoryGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            EditAction::make(),
        ];
    }
}
