<?php

namespace App\Filament\Resources\Brands\Pages;

use App\Filament\Resources\Brands\BrandResource;
use App\Filament\Resources\Concerns\AlignsFormActionsStart;
use App\Filament\Resources\Concerns\MutatesSlugFromName;
use Filament\Resources\Pages\CreateRecord;

class CreateBrand extends CreateRecord
{
    use AlignsFormActionsStart;
    use MutatesSlugFromName;

    protected static string $resource = BrandResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data = $this->applySlugFromName($data);
        $data['sr'] = $data['sr'] ?? 0;

        return $data;
    }
}
