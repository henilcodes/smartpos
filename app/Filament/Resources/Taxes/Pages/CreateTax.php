<?php

namespace App\Filament\Resources\Taxes\Pages;

use App\Filament\Resources\Concerns\HasFormActionsAtTopAndBottom;
use App\Filament\Resources\Taxes\TaxResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTax extends CreateRecord
{
    use HasFormActionsAtTopAndBottom;

    protected static string $resource = TaxResource::class;
}
