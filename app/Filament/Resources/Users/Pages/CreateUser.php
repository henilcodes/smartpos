<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Concerns\AlignsFormActionsStart;
use App\Filament\Resources\Users\UserResource;
use App\Support\AppNotifier;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateUser extends CreateRecord
{
    use AlignsFormActionsStart;

    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['email_verified_at'] = ! empty($data['is_verified']) ? now() : null;

        unset($data['is_verified'], $data['passwordConfirmation'], $data['permissions']);

        if (! auth()->user()?->isSuperAdmin()) {
            unset($data['is_super_admin']);
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        $permissions = $this->form->getState()['permissions'] ?? [];

        if (! empty($permissions) && ! $this->record->isSuperAdmin()) {
            $this->record->applyModulePermissions($permissions);
        } else {
            $this->record->syncModulePermissions();
        }

        AppNotifier::notifySuperAdmins(
            'New user created',
            "User account \"{$this->record->name}\" ({$this->record->email}) was created.",
            'info',
        );

        if ($actor = Auth::user()) {
            AppNotifier::send(
                $actor,
                'User created',
                "You created the user account for {$this->record->name}.",
                'success',
            );
        }
    }
}
