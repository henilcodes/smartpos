<?php

namespace App\Filament\Resources\Inventories\Pages;

use App\Filament\Resources\Concerns\AlignsFormActionsStart;
use App\Filament\Resources\Inventories\InventoryResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateInventory extends CreateRecord
{
    use AlignsFormActionsStart;

    protected static string $resource = InventoryResource::class;

    public function mount(): void
    {
        parent::mount();

        $this->form->fill([
            'code' => 'INV-'.Str::upper(Str::random(8)),
            'date' => now(),
            'status' => 'pending',
        ]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (blank($data['code'] ?? null)) {
            $data['code'] = 'INV-'.Str::upper(Str::random(8));
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}
