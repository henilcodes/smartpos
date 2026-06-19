<?php

namespace App\Filament\Resources\Taxes\Pages;

use App\Filament\Resources\Concerns\HasFormActionsAtTopAndBottom;
use App\Filament\Resources\Taxes\TaxResource;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTax extends EditRecord
{
    use HasFormActionsAtTopAndBottom;

    protected static string $resource = TaxResource::class;

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
