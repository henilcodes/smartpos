<?php

namespace App\Filament\Resources\TaxGroups\Pages;

use App\Filament\Resources\TaxGroups\TaxGroupResource;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTaxGroup extends ViewRecord
{
    protected static string $resource = TaxGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            ActionGroup::make([
                DeleteAction::make(),
            ])
                ->tooltip('More actions'),
        ];
    }
}
