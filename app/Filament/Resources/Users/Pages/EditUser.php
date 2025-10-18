<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

     protected function mutateFormDataBeforeSave(array $data): array
    {
        // Ne pas hasher le mot de passe s'il n'est pas modifié
        if (empty($data['password'])) {
            unset($data['password']);
        }
        
        return $data;
    }

     protected function afterSave(): void
    {
        // Synchroniser les rôles
        if (isset($this->data['roles'])) {
            $this->record->roles()->sync($this->data['roles']);
        }
        
        // Synchroniser les permissions directes
        if (isset($this->data['permissions'])) {
            $this->record->permissions()->sync($this->data['permissions']);
        }
    }
}
