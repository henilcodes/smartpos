<?php

namespace App\Filament\Resources\Customers\Pages;

use App\Filament\Resources\Concerns\AlignsFormActionsStart;
use App\Filament\Resources\Customers\CustomerResource;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditCustomer extends EditRecord
{
    use AlignsFormActionsStart;

    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
                ->tooltip('Actions'),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['preferences'] = $this->normalizePreferences($data['preferences'] ?? []);

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['preferences'] = $this->normalizePreferences($data['preferences'] ?? []);

        return $data;
    }

    /**
     * @param  array<string, mixed>  $preferences
     * @return array<string, bool>
     */
    protected function normalizePreferences(array $preferences): array
    {
        return [
            'email_notifications' => (bool) ($preferences['email_notifications'] ?? false),
            'sms_notifications' => (bool) ($preferences['sms_notifications'] ?? false),
            'whatsapp_notifications' => (bool) ($preferences['whatsapp_notifications'] ?? false),
        ];
    }
}
