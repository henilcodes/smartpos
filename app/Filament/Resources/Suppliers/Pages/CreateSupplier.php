<?php

namespace App\Filament\Resources\Suppliers\Pages;

use App\Filament\Resources\Concerns\AlignsFormActionsStart;
use App\Filament\Resources\Suppliers\SupplierResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSupplier extends CreateRecord
{
    use AlignsFormActionsStart;

    protected static string $resource = SupplierResource::class;
}
