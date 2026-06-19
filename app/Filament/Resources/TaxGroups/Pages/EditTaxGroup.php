<?php

namespace App\Filament\Resources\TaxGroups\Pages;

use App\Filament\Resources\Concerns\HasFormActionsAtTopAndBottom;
use App\Filament\Resources\TaxGroups\TaxGroupResource;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTaxGroup extends EditRecord
{
    use HasFormActionsAtTopAndBottom;

    protected static string $resource = TaxGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                DeleteAction::make(),
            ])
                ->tooltip('Actions'),
        ];
    }
}
