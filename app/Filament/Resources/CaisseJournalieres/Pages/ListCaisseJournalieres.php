<?php

namespace App\Filament\Resources\CaisseJournalieres\Pages;

use App\Filament\Resources\CaisseJournalieres\CaisseJournaliereResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCaisseJournalieres extends ListRecords
{
    protected static string $resource = CaisseJournaliereResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
