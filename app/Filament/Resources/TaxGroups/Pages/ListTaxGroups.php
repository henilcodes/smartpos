<?php

namespace App\Filament\Resources\TaxGroups\Pages;

use App\Filament\Resources\TaxGroups\TaxGroupResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListTaxGroups extends ListRecords
{
    protected static string $resource = TaxGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New tax group')
                ->icon(Heroicon::Plus),
        ];
    }
}
