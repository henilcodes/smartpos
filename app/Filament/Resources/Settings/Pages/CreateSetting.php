<?php

namespace App\Filament\Resources\Settings\Pages;

use App\Filament\Resources\Concerns\HasFormActionsAtTopAndBottom;
use App\Filament\Resources\Settings\SettingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSetting extends CreateRecord
{
    use HasFormActionsAtTopAndBottom;

    protected static string $resource = SettingResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['payload'] = $data['payload'] ?? [];

        return $data;
    }
}
