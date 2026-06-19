<?php

namespace App\Filament\Resources\CategoryGroups\Pages;

use App\Filament\Resources\CategoryGroups\CategoryGroupResource;
use App\Filament\Resources\Concerns\AlignsFormActionsStart;
use Filament\Resources\Pages\CreateRecord;

class CreateCategoryGroup extends CreateRecord
{
    use AlignsFormActionsStart;

    protected static string $resource = CategoryGroupResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['sr'] = $data['sr'] ?? 0;

        return $data;
    }
}
