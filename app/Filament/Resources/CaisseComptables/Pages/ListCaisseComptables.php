<?php

namespace App\Filament\Resources\CaisseComptables\Pages;

use App\Filament\Resources\CaisseComptables\CaisseComptableResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCaisseComptables extends ListRecords
{
    protected static string $resource = CaisseComptableResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
