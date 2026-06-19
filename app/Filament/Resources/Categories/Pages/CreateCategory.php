<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use App\Filament\Resources\Concerns\AlignsFormActionsStart;
use App\Filament\Resources\Concerns\MutatesSlugFromName;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    use AlignsFormActionsStart;
    use MutatesSlugFromName;

    protected static string $resource = CategoryResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data = $this->applySlugFromName($data);
        $data['sr'] = $data['sr'] ?? 0;

        return $data;
    }
}
