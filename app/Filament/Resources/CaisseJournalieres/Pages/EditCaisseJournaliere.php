<?php

namespace App\Filament\Resources\CaisseJournalieres\Pages;

use App\Filament\Resources\CaisseJournalieres\CaisseJournaliereResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCaisseJournaliere extends EditRecord
{
    protected static string $resource = CaisseJournaliereResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
