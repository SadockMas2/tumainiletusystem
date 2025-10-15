<?php

namespace App\Filament\Resources\CompteBancaires\Pages;

use App\Filament\Resources\CompteBancaires\CompteBancaireResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCompteBancaire extends EditRecord
{
    protected static string $resource = CompteBancaireResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
