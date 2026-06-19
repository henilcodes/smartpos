<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Concerns\HasFormActionsAtTopAndBottom;
use App\Filament\Resources\Products\ProductResource;
use App\Support\AppNotifier;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CreateProduct extends CreateRecord
{
    use HasFormActionsAtTopAndBottom;

    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (blank($data['sku'] ?? null)) {
            $data['sku'] = Str::upper(Str::slug($data['name'] ?? '', '-'));
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->record]);
    }

    protected function afterCreate(): void
    {
        $user = Auth::user();

        if ($user) {
            AppNotifier::notifyForModelAction($user, $this->record, 'created');
        }
    }
}
