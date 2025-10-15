<?php

namespace App\Filament\Resources\Epargnes\Pages;

use App\Filament\Resources\Epargnes\EpargneResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;

class CreateEpargne extends CreateRecord
{
    protected static string $resource = EpargneResource::class;
// protected function mutateFormDataBeforeCreate(array $data): array
// {
//     if (empty($data['cycle_id'])) {
//         throw ValidationException::withMessages([
//             'client_id' => 'Ce client n’a pas de cycle ouvert. Veuillez d’abord créer un cycle.',
//         ]);
//     }

//     return $data;
// }

protected function getRedirectUrl(): string
{
    return $this->getResource()::getUrl('index');
}

}
