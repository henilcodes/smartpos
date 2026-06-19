<?php

namespace App\Filament\Resources\Customers\Pages;

use App\Filament\Resources\Concerns\AlignsFormActionsStart;
use App\Filament\Resources\Customers\CustomerResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomer extends CreateRecord
{
    use AlignsFormActionsStart;

    protected static string $resource = CustomerResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['preferences'] = $this->normalizePreferences($data['preferences'] ?? []);

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
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
