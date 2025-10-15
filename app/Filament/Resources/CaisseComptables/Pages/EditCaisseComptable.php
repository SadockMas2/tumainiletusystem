<?php

namespace App\Filament\Resources\CaisseComptables\Pages;

use App\Filament\Resources\CaisseComptables\CaisseComptableResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCaisseComptable extends EditRecord
{
    protected static string $resource = CaisseComptableResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
